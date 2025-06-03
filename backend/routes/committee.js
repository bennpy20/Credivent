require('dotenv').config();

const express = require('express');
const router = express.Router();
const bcrypt = require('bcrypt');
const Event = require('../models/Event');
const EventSession = require('../models/EventSession');
const Speaker = require('../models/Speaker');
const generateEventId = require('../utils/generateEventId');
const generateEventSessionId = require('../utils/generateEventSessionId');
const generateSpeakerId = require('../utils/generateSpeakerId');

//// Route untuk Panitia (Committee) kelola event
router.get('/committee-event-index', async (req, res) => {
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

            const formattedStartEvent = new Date(session.session_start);
            const formattedEndEvent = new Date(session.session_end);

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
})

module.exports = router;