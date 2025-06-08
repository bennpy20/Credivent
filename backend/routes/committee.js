require('dotenv').config();

const express = require('express');
const router = express.Router();
const bcrypt = require('bcrypt');
const Event = require('../models/Event');
const EventSession = require('../models/EventSession');
const Speaker = require('../models/Speaker');
const Registration = require('../models/Registration');
const Attendance = require('../models/Attendance');
const generateEventId = require('../utils/generateEventId');
const generateEventSessionId = require('../utils/generateEventSessionId');
const generateSpeakerId = require('../utils/generateSpeakerId');
const moment = require('moment-timezone');
const updateEventStatus = require('../utils/updateEventStatus');

//// Route untuk Panitia (Committee) kelola event
router.get('/committee-event-index', async (req, res) => {
    await updateEventStatus();
    const events = await Event.findAll();
    res.json(events);
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

            const formattedStartEvent = moment.tz(session.session_start, 'YYYY-MM-DD HH:mm', 'Asia/Bangkok').format('YYYY-MM-DD HH:mm:ss');
            const formattedEndEvent = moment.tz(session.session_end, 'YYYY-MM-DD HH:mm', 'Asia/Bangkok').format('YYYY-MM-DD HH:mm:ss');


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


// Scan untuk QRCode
router.post('/committee-scanqr-store', async (req, res) => {
    const { qr_data } = req.body;

    try {
        const data = JSON.parse(qr_data); // Parsing JSON dari QR

        const registration = await Registration.findOne({ where: { id: data.registration_id } });

        if (!registration || registration.qrcode === null) {
            return res.status(200).json({ valid: false, message: "Data registrasi tidak ditemukan" });
        }

        const session = await EventSession.findByPk(data.session_id);
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

module.exports = router;