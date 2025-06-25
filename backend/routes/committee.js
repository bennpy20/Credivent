require('dotenv').config();

const express = require('express');
const router = express.Router();
const bcrypt = require('bcrypt');
const Event = require('../models/Event');
const EventSession = require('../models/EventSession');
const Speaker = require('../models/Speaker');
const Registration = require('../models/Registration');
const Attendance = require('../models/Attendance');
const User = require('../models/User');
const generateEventId = require('../utils/generateEventId');
const generateEventSessionId = require('../utils/generateEventSessionId');
const generateSpeakerId = require('../utils/generateSpeakerId');
const moment = require('moment-timezone');
const updateEventStatus = require('../utils/updateEventStatus');

//// Route untuk Panitia (Committee) kelola event
router.get('/committee-event-index', async (req, res) => {
    const userId = req.query.user_id;

    if (!userId) {
        return res.status(400).json({ error: 'user_id is required' });
    }

    try {
        await updateEventStatus();

        const events = await Event.findAll({
            where: { user_id: userId }
        });

        const withSessions = await Promise.all(events.map(async event => {
            const sessions = await EventSession.findAll({
                where: { event_id: event.id }
            });

            const sessionWithSpeakers = await Promise.all(sessions.map(async session => {
                const speaker = await Speaker.findOne({ where: { event_session_id: session.id } });

                return {
                    ...session.toJSON(),
                    name: speaker?.name ?? '',
                    speaker_image: speaker?.speaker_image ?? ''
                };
            }));

            return {
                ...event.toJSON(),
                event_sessions: sessionWithSpeakers
            };
        }));

        res.json(withSessions);
    } catch (error) {
        console.error("Gagal ambil event + sessions:", error);
        res.status(500).json({ error: "Gagal ambil data" });
    }
});

router.post('/committee-event-store', async (req, res) => {
    const {
        name,
        location,
        poster_link,
        max_participants,
        transaction_fee,
        event_status,
        start_date,
        end_date,
        user_id,
        event_sessions = []
    } = req.body;

    const t = await Event.sequelize.transaction();

    try {
        // Buat ID event baru (jika kamu punya custom ID)
        const newEventId = await generateEventId();

        // Simpan event baru ke database
        const newEvent = await Event.create({
            id: newEventId,
            name,
            location,
            poster_link,
            max_participants,
            transaction_fee,
            event_status,
            start_date,
            end_date,
            user_id
        }, { transaction: t });

        // Ambil ID awal event session
        let baseId = await generateEventSessionId();
        let baseNumber = parseInt(baseId.split('-')[1]);

        // Ambil ID awal speaker
        let baseSpeakerId = await generateSpeakerId(); // e.g. SPK-002
        let baseSpeakerNumber = parseInt(baseSpeakerId.split('-')[1]);

        // Simpan setiap sesi dan speaker
        for (let i = 0; i < event_sessions.length; i++) {
            const session = event_sessions[i];

            const newEventSessionId = `ESE-${(baseNumber + i).toString().padStart(3, '0')}`;

            const formattedStartEvent = new Date(session.session_start);
            formattedStartEvent.setHours(formattedStartEvent.getHours() + 7);

            const formattedEndEvent = new Date(session.session_end);
            formattedEndEvent.setHours(formattedEndEvent.getHours() + 7);


            const newSession = await EventSession.create({
                id: newEventSessionId,
                event_id: newEvent.id,
                session: i + 1,  // simpan urutan sesi ke kolom 'session'
                title: session.title,
                session_start: formattedStartEvent,
                session_end: formattedEndEvent,
                description: session.description
            }, { transaction: t });

            const newSpeakerId = `SPK-${(baseSpeakerNumber + i).toString().padStart(3, '0')}`;

            await Speaker.create({
                id: newSpeakerId,
                event_session_id: newSession.id,
                name: session.name,
                speaker_image: session.speaker_image || null
            }, { transaction: t });
        }

        await t.commit();

        res.json({
            status: 'success',
            event: {
                id: newEvent.id,
                name: newEvent.name,
                poster_link: newEvent.poster_link,
            }
        });

    } catch (error) {
        console.error(error);
        res.status(500).json({ status: 'error', message: 'Server error' });
    }
});

router.put('/committee-event-update/:id', async (req, res) => {
    const {
        name,
        location,
        max_participants,
        transaction_fee,
        start_date,
        end_date
    } = req.body;

    try {
        const event = await Event.findByPk(req.params.id);

        if (!event) {
            return res.status(404).json({ status: 'fail', message: 'Event tidak ditemukan' });
        }

        // Update field
        event.name = name;
        event.location = location;
        event.max_participants = max_participants;
        event.transaction_fee = transaction_fee;
        event.start_date = start_date;
        event.end_date = end_date;

        await event.save();

        res.json({ status: 'success', message: 'Event berhasil diupdate' });

    } catch (error) {
        console.error(error);
        res.status(500).json({ status: 'error', message: 'Server error saat update event' });
    }
});

router.delete('/committee-event-destroy/:id', async (req, res) => {
    const user_id = req.body.user_id;

    try {
        const event = await Event.findByPk(req.params.id);

        if (!event) {
            return res.status(404).json({ status: 'fail', message: 'Event tidak ditemukan' });
        }

        // Cek kepemilikan
        if (event.user_id !== user_id) {
            return res.status(403).json({ status: 'fail', message: 'Anda tidak diizinkan menghapus event ini' });
        }

        // Cari semua session yang terkait
        const sessions = await EventSession.findAll({ where: { event_id: event.id } });

        for (const session of sessions) {
            const speakers = await Speaker.findAll({ where: { event_session_id: session.id } });

            // Hapus speaker
            await Speaker.destroy({ where: { event_session_id: session.id } });
        }

        // Hapus semua event_session
        await EventSession.destroy({ where: { event_id: event.id } });

        // Terakhir, hapus event
        await event.destroy();

        res.json({ status: 'success', message: 'Event dan data terkait berhasil dihapus' });

    } catch (error) {
        console.error('Gagal menghapus event dan relasi:', error);
        res.status(500).json({ status: 'error', message: 'Server error saat menghapus event' });
    }
});

//// Tambah, update, delete untuk sesi
router.get('/committee-session-index', async (req, res) => {
    try {
        const events = await Event.findAll({ order: [['createdAt', 'DESC']] });

        const results = await Promise.all(events.map(async (event) => {
            // Ambil semua sesi berdasarkan event_id
            const sessions = await EventSession.findAll({
                where: { event_id: event.id }
            });

            // Gabungkan sesi ke event
            return {
                ...event.toJSON(),
                event_sessions: sessions.map(session => session.toJSON())
            };
        }));

        res.status(200).json(results);
    } catch (error) {
        console.error("Gagal ambil event + sessions:", error);
        res.status(500).json({ error: "Gagal ambil data" });
    }
});

router.post('/committee-session-store', async (req, res) => {
    try {
        const {
            session,
            event_id,
            title,
            session_start,
            session_end,
            description,
            name,
            speaker_image
        } = req.body;

        // Konversi waktu ke Date
        const sessionStartUTC = new Date(session_start);
        const sessionEndUTC = new Date(session_end);

        const sessionStartWIB = new Date(sessionStartUTC.getTime() + 7 * 60 * 60 * 1000);
        const sessionEndWIB = new Date(sessionEndUTC.getTime() + 7 * 60 * 60 * 1000);


        const sessionId = await generateEventSessionId();
        const speakerId = await generateSpeakerId();

        const newSession = await EventSession.create({
            id: sessionId,
            session,
            event_id,
            title,
            session_start: sessionStartWIB,
            session_end: sessionEndWIB,
            description
        });

        await Speaker.create({
            id: speakerId,
            event_session_id: sessionId,
            name,
            speaker_image: speaker_image || null
        });

        res.json({ status: 'success', message: 'Sesi berhasil ditambahkan' });
    } catch (error) {
        console.error('Tambah sesi error:', error);
        res.status(500).json({ status: 'error', message: 'Server error' });
    }
});

router.get('/committee-session-edit', async (req, res) => {
    const eventId = req.query.event_id;

    if (!eventId) {
        return res.status(400).json({ error: 'event_id is required' });
    }

    try {
        const event = await Event.findOne({ where: { id: eventId } });

        if (!event) {
            return res.status(404).json({ error: 'Event tidak ditemukan' });
        }

        const sessions = await EventSession.findAll({ where: { event_id: eventId } });

        res.json({
            ...event.toJSON(),
            event_sessions: sessions.map(s => s.toJSON())
        });
    } catch (error) {
        console.error('Gagal ambil event + sessions:', error);
        res.status(500).json({ error: 'Internal server error' });
    }
});

router.put('/committee-session-update/:id', async (req, res) => {
    try {
        const sessionId = req.params.id;
        const data = req.body;

        // Validasi minimal
        if (!data.title || !data.session_start || !data.session_end || !data.description) {
            return res.status(400).json({ error: 'Data tidak lengkap' });
        }

        // Validasi dan konversi tanggal
        const sessionStart = new Date(data.session_start);
        const sessionEnd = new Date(data.session_end);

        if (isNaN(sessionStart.getTime()) || isNaN(sessionEnd.getTime())) {
            return res.status(400).json({ error: 'Format tanggal tidak valid' });
        }

        // Update event session
        await EventSession.update({
            title: data.title,
            session_start: sessionStart,
            session_end: sessionEnd,
            description: data.description
        }, {
            where: { id: sessionId }
        });

        // Update speaker
        const speaker = await Speaker.findOne({ where: { event_session_id: sessionId } });

        if (speaker) {
            await Speaker.update({
                name: data.name,
                speaker_image: data.speaker_image ?? speaker.speaker_image
            }, {
                where: { event_session_id: sessionId }
            });
        } else {
            const speakerId = await generateSpeakerId();
            await Speaker.create({
                id: speakerId,
                event_session_id: sessionId,
                name: data.name,
                speaker_image: data.speaker_image ?? null
            });
        }

        res.json({ message: "Sesi berhasil diupdate" });
    } catch (error) {
        console.error('Error mengupdate sesi:', error);
        res.status(500).json({ error: 'Internal server error' });
    }
});


router.delete('/committee-session-destroy/:id', async (req, res) => {
    try {
        const sessionId = req.params.id;

        await Speaker.destroy({ where: { event_session_id: sessionId } });

        await EventSession.destroy({ where: { id: sessionId } });

        res.json({ message: 'Session deleted successfully' });
    } catch (error) {
        console.error('Error deleting session:', error);
        res.status(500).json({ error: 'Internal server error' });
    }
});


// Scan untuk QRCode
router.post('/committee-scanqr-store', async (req, res) => {
    const { qr_data, user_id } = req.body;

    try {
        const data = JSON.parse(qr_data); // Parsing JSON dari QR

        const registration = await Registration.findOne({ where: { id: data.registration_id } });

        if (!registration || registration.qrcode === null) {
            return res.status(200).json({ valid: false, message: "Data registrasi tidak ditemukan" });
        }

        const session = await EventSession.findByPk(data.session_id);

        const event = await Event.findByPk(session.event_id);

        if (!event || event.user_id !== user_id) {
            return res.status(403).json({ valid: false, message: "Event ini bukanlah event yang Anda buat, tidak bisa scan" });
        }
        const nowUTC = new Date();
        
        // Geser UTC ke WIB (Asia/Jakarta = UTC+7)
        const nowWIB = new Date(nowUTC.getTime() + (7 * 60 * 60 * 1000));
        const sessionStart = new Date(session.session_start);
        const sessionEnd = new Date(session.session_end);

        if (nowWIB < sessionStart) {
            return res.status(200).json({ valid: false, message: "Acara belum dimulai" });
        }

        if (nowWIB > sessionEnd) {
            return res.status(200).json({ valid: false, message: "Acara telah berakhir" });
        }

        const attendance = await Attendance.findOne({ where: { registration_id: data.registration_id } });

        if (!attendance) {
            return res.status(404).json({ valid: false, message: 'Data attendance tidak ditemukan' });
        }

        if (attendance.validity === 2) {
            return res.status(400).json({ valid: false, message: 'Peserta sudah tercatat hadir' });
        }

        attendance.validity = 2;
        await attendance.save();

        // QR valid dan dalam waktu acara
        return res.status(200).json({
            valid: true,
            detail: {
                event: data.event_name,
                session: data.session_title,
                waktu: new Date().toLocaleString('id-ID', { timeZone: 'Asia/Jakarta' })
            }
        });
    } catch (err) {
        console.error("Verifikasi QR gagal:", err);
        return res.status(500).json({ valid: false, message: "Format kode tidak dikenali" });
    }
});


//// Panitia upload sertifikat
router.get('/committee-certificate-index', async (req, res) => {
    try {
        const now = new Date();
        const attendances = await Attendance.findAll({
            where: { validity: 2 }
        });

        const results = await Promise.all(attendances.map(async (att) => {
            const registration = await Registration.findByPk(att.registration_id);
            if (!registration) return null;

            const user = await User.findByPk(registration.user_id);
            const session = await EventSession.findByPk(registration.event_session_id);
            if (!user || !session) return null;

            // Pastikan hanya sesi yang sudah selesai
            const sessionEnd = new Date(session.session_end);
            if (sessionEnd >= now) return null;

            const event = await Event.findByPk(session.event_id);
            if (!event) return null;

            return {
                attendance_id: att.id,
                user: {
                    name: user.name,
                    email: user.email
                },
                session: {
                    session: session.session,
                    title: session.title,
                    session_start: session.session_start,
                    session_end: session.session_end
                },
                event: {
                    name: event.name
                },
                certificate_link: att.certificate_link
            };
        }));

        const filteredResults = results.filter(item => item !== null);
        res.json(filteredResults);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Internal Server Error' });
    }
});


router.put('/committee-certificate-update/:id', async (req, res) => {
    const { certificate_link } = req.body;

    try {
        const attendance = await Attendance.findByPk(req.params.id);
        if (!attendance) {
            return res.status(404).json({ message: 'Data kehadiran tidak ditemukan' });
        }

        attendance.certificate_link = certificate_link;
        await attendance.save();

        res.json({ status: 'success', message: 'Sertifikat berhasil diupload' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ status: 'error', message: 'Server error' });
    }
});

module.exports = router;