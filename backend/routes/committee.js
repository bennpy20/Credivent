require('dotenv').config();

const express = require('express');
const router = express.Router();
const bcrypt = require('bcrypt');
const Event = require('../models/Event');
const generateEventId = require('../utils/generateEventId');

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
        end_date
    } = req.body;

    try {
        // Cek apakah nama event sudah ada (opsional)
        const existingEvent = await Event.findOne({ where: { name } });
        if (existingEvent) {
            return res.status(400).json({ status: 'fail', message: 'Nama event sudah terdaftar' });
        }

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
            end_date
        });

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