require('dotenv').config();

const express = require('express');
const router = express.Router();
const bcrypt = require('bcrypt');
const Event = require('../models/Event');
const EventSession = require('../models/EventSession');
const Speaker = require('../models/Speaker');
const User = require('../models/User');
const Registration = require('../models/Registration');
const Attendance = require('../models/Attendance');
const generateRegistrationId = require('../utils/generateRegistrationId');
const generateAttendanceId = require('../utils/generateAttendanceId');
const updateEventStatus = require('../utils/updateEventStatus');
const { Op } = require('sequelize');

//// Route untuk Panitia (Committee) kelola event
router.get('/member-event-index', async (req, res) => {
    try {
        await updateEventStatus();

        // Ambil semua event dengan status 1 atau 2
        const events = await Event.findAll({
            where: { event_status: [1, 2] }
        });

        // Untuk setiap event, hitung total peserta berdasarkan session -> registration
        const results = await Promise.all(events.map(async (event) => {
            // Cari semua session di event ini
            const sessions = await EventSession.findAll({
                where: { event_id: event.id }
            });

            let totalParticipants = 0;

            // Untuk setiap session, hitung jumlah registrasi
            for (const session of sessions) {
                const registrations = await Registration.findAll({
                    where: { event_session_id: session.id }
                });
                totalParticipants += registrations.length;
            }

            return {
                id: event.id,
                name: event.name,
                location: event.location,
                poster_link: event.poster_link,
                transaction_fee: event.transaction_fee,
                event_status: event.event_status,
                start_date: event.start_date,
                end_date: event.end_date,
                max_participants: event.max_participants,
                registered_participants: totalParticipants,
                available_capacity: event.max_participants - totalParticipants
            };
        }));
        res.json(results);
    } catch (error) {
        console.error('Error fetching data:', error);
        res.status(500).json({ error: 'Internal server error' });
    }
});


router.get('/member-event-show/:id', async (req, res) => {
    await updateEventStatus();
    try {
        // 1. Cari Event berdasarkan ID
        const event = await Event.findByPk(req.params.id);

        if (!event) {
            return res.status(404).json({ message: 'Event tidak ditemukan' });
        }

        // 2. Cari EventSession yang terkait dengan Event ini
        const eventSessions = await EventSession.findAll({
            where: { event_id: event.id }
        });

        // 3. Ambil semua speaker dalam satu query
        const sessionIds = eventSessions.map(session => session.id);
        const allSpeakers = await Speaker.findAll({
            where: {
                event_session_id: sessionIds
            }
        });

        // 4. Kelompokkan speaker berdasarkan event_session_id
        const speakersGrouped = {};
        allSpeakers.forEach(speaker => {
            const sessionId = speaker.event_session_id;
            if (!speakersGrouped[sessionId]) {
                speakersGrouped[sessionId] = [];
            }
            speakersGrouped[sessionId].push(speaker);
        });

        // 5. Gabungkan speaker ke setiap session
        const eventSessionsWithSpeakers = eventSessions.map(session => {
            const sessionData = session.toJSON(); // konversi model ke object biasa
            sessionData.speakers = speakersGrouped[session.id] || [];
            return sessionData;
        });

        // 6. Kirimkan response
        res.json({
            event,
            event_sessions: eventSessionsWithSpeakers,
        });

    } catch (error) {
        console.error('Error fetching event data:', error);
        res.status(500).json({ message: 'Terjadi kesalahan pada server' });
    }
});

// Member batalkan event sblm bayar
router.delete('/member-registration-destroy/:id', async (req, res) => {
    const registrationId = req.params.id;

    try {
        // Cari registrasi
        const registration = await Registration.findByPk(registrationId);

        if (!registration) {
            return res.status(404).json({ error: 'Pendaftaran tidak ditemukan' });
        }

        // Jika sudah bayar, tidak bisa dibatalkan
        if (registration.payment_proof) {
            return res.status(400).json({ error: 'Tidak dapat membatalkan, pembayaran sudah dilakukan' });
        }

        // Cek apakah ada attendance terkait
        const attendance = await Attendance.findOne({
            where: { registration_id: registrationId }
        });

        // Hapus attendance terlebih dahulu jika ada
        if (attendance) {
            await attendance.destroy();
        }

        // Hapus registrasi
        await registration.destroy();

        return res.json({ message: 'Pendaftaran berhasil dibatalkan' });
    } catch (error) {
        console.error('Error cancel registration:', error);
        res.status(500).json({ error: 'Server error' });
    }
});


//// Mengurus registrasi event untuk member

router.get('/member-registration-show/:id', async (req, res) => {
    await updateEventStatus();
    const userId = req.query.id;

    try {
        const user = await User.findByPk(userId);
        if (!user) {
            return res.status(404).json({ message: 'User tidak ditemukan' });
        }

        const event = await Event.findByPk(req.params.id);

        if (!event) {
            return res.status(404).json({ message: 'Event tidak ditemukan' });
        }

        // 2. Cari EventSession yang terkait dengan Event ini
        const eventSessions = await EventSession.findAll({
            where: { event_id: event.id }
        });

        // 3. Ambil semua speaker dalam satu query
        const sessionIds = eventSessions.map(session => session.id);
        const allSpeakers = await Speaker.findAll({
            where: {
                event_session_id: sessionIds
            }
        });

        // 4. Kelompokkan speaker berdasarkan event_session_id
        const speakersGrouped = {};
        allSpeakers.forEach(speaker => {
            const sessionId = speaker.event_session_id;
            if (!speakersGrouped[sessionId]) {
                speakersGrouped[sessionId] = [];
            }
            speakersGrouped[sessionId].push(speaker);
        });

        // 5. Gabungkan speaker ke setiap session
        const eventSessionsWithSpeakers = eventSessions.map(session => {
            const sessionData = session.toJSON(); // konversi model ke object biasa
            sessionData.speakers = speakersGrouped[session.id] || [];
            return sessionData;
        });

        // 6. Kirimkan response
        res.json({
            user,
            event,
            event_sessions: eventSessionsWithSpeakers,
        });

    } catch (error) {
        console.error('Error fetching event data:', error);
        res.status(500).json({ message: 'Terjadi kesalahan pada server' });
    }
});

router.post('/member-registration-store', async (req, res) => {
    const { user_id, session_ids } = req.body;

    if (!user_id || !Array.isArray(session_ids)) {
        return res.status(400).json({ message: 'Data tidak lengkap' });
    }

    try {
        // Cek apakah user sudah pernah mendaftar ke salah satu session
        const existing = await Registration.findOne({
            where: {
                user_id,
                event_session_id: session_ids  // Sequelize akan mengkonversi jadi IN()
            }
        });

        if (existing) {
            return res.status(409).json({ message: 'Kamu sudah mendaftar untuk salah satu sesi di event ini' });
        }

        const registrations = [];

        for (const sessionId of session_ids) {
            // Buat ID registration baru (jika kamu punya custom ID)
            const newRegistrationId = await generateRegistrationId();
            // 1. Buat registration
            const reg = await Registration.create({
                id: newRegistrationId,
                user_id,
                event_session_id: sessionId,
                payment_status: 1,
                payment_proof: '',
                qrcode: ''
            });

            // Buat ID registration baru (jika kamu punya custom ID)
            const newAttendanceId = await generateAttendanceId();

            // 2. Buat attendance untuk registration ini
            await Attendance.create({
                id: newAttendanceId,
                validity: 1,
                certificate_link: '',
                registration_id: reg.id
            });

            registrations.push(reg);
        }

        return res.status(201).json({ message: 'Registrasi dan attendance berhasil dibuat', registrations });
    } catch (error) {
        console.error('Gagal saat insert registrasi dan attendance:', error);
        return res.status(500).json({ message: 'Terjadi kesalahan saat menyimpan data' });
    }
});

router.get('/member-registration-index', async (req, res) => {
    await updateEventStatus();
    const userId = req.query.id;

    try {
        const registrations = await Registration.findAll({
            where: { user_id: userId }
        });

        const results = await Promise.all(registrations.map(async (reg) => {
            const session = await EventSession.findByPk(reg.event_session_id);
            if (!session) return null;

            const event = await Event.findByPk(session.event_id);
            if (!event) return null;

            const attendance = await Attendance.findOne({
                where: { registration_id: reg.id }
            });

            return {
                registration_id: reg.id,
                payment_proof: reg.payment_proof,
                payment_status: reg.payment_status,
                qrcode: reg.qrcode,
                session: {
                    id: session.id,
                    session: session.session,
                    title: session.title,
                    session_start: session.session_start,
                    session_end: session.session_end,
                    description: session.description
                },
                event: {
                    id: event.id,
                    name: event.name,
                    poster_link: event.poster_link,
                    max_participants: event.max_participants,
                    transaction_fee: event.transaction_fee,
                    event_status: event.event_status,
                    start_date: event.start_date,
                    end_date: event.end_date
                },
                attendance: attendance ? {
                    id: attendance.id,
                    validity: attendance.validity,
                    certificate_link: attendance.certificate_link
                } : null
            };
        }));

        // Filter null jika ada data tidak ditemukan
        const filteredResults = results.filter(item => item !== null);

        return res.json(filteredResults);
    } catch (error) {
        console.error('Gagal mengambil data registrasi user:', error);
        return res.status(500).json({ message: 'Terjadi kesalahan pada server' });
    }
});

router.put('/member-registration-update/:id', async (req, res) => {
    const { payment_proof } = req.body;
    try {
        const registration = await Registration.findByPk(req.params.id);
        if (!registration) return res.status(404).json({ message: 'Data tidak ditemukan' });

        registration.payment_proof = payment_proof;
        await registration.save();

        res.json({ status: 'success', message: 'Bukti pembayaran diperbarui' });
    } catch (err) {
        console.error(err);
        res.status(500).json({ status: 'error', message: 'Server error' });
    }
});

//// Member melihat sertifikat
router.get('/member-certificate-index', async (req, res) => {
    const { user_id } = req.query;

    try {
        const registrations = await Registration.findAll({ where: { user_id } });
        const regIds = registrations.map(r => r.id);

        const attendances = await Attendance.findAll({
            where: {
                registration_id: regIds,
                validity: 2,
                certificate_link: { [Op.ne]: null }
            }
        });

        const results = await Promise.all(attendances.map(async (att) => {
            const reg = registrations.find(r => r.id === att.registration_id);
            const session = await EventSession.findByPk(reg.event_session_id);
            const event = await Event.findByPk(session.event_id);

            return {
                certificate_link: att.certificate_link,
                session: {
                    session: session.session,
                    title: session.title,
                    session_start: session.session_start,
                    session_end: session.session_end
                },
                event: {
                    name: event.name
                }
            };
        }));

        res.json(results);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Internal server error' });
    }
});

router.get('/member-speaker-index', async (req, res) => {
    try {
        const speakers = await Speaker.findAll({
            order: [['name', 'ASC']]
        });
        res.status(200).json(speakers);
    } catch (error) {
        console.error("Gagal mengambil data pembicara:", error);
        res.status(500).json({ error: 'Gagal mengambil data pembicara' });
    }
});

//// Dashboard member
router.get('/member-dashboard-index', async (req, res) => {
    try {
        await updateEventStatus();

        // Ambil semua event status 1 atau 2
        const events = await Event.findAll({ where: { event_status: [1, 2] } });

        const eventResults = await Promise.all(events.map(async (event) => {
            const sessions = await EventSession.findAll({ where: { event_id: event.id } });

            let totalParticipants = 0;
            for (const session of sessions) {
                const registrations = await Registration.count({ where: { event_session_id: session.id } });
                totalParticipants += registrations;
            }

            return {
                id: event.id,
                name: event.name,
                location: event.location,
                poster_link: event.poster_link,
                transaction_fee: event.transaction_fee,
                event_status: event.event_status,
                start_date: event.start_date,
                end_date: event.end_date,
                max_participants: event.max_participants,
                registered_participants: totalParticipants,
                available_capacity: event.max_participants - totalParticipants
            };
        }));

        const speakers = await Speaker.findAll();

        return res.status(200).json({
            events: eventResults,
            speakers
        });
    } catch (error) {
        console.error("Gagal memuat data dashboard member:", error);
        res.status(500).json({ error: 'Gagal memuat data dashboard member' });
    }
});


module.exports = router;