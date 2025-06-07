require('dotenv').config();

const express = require('express');
const router = express.Router();
const bcrypt = require('bcrypt');
const Event = require('../models/Event');
const EventSession = require('../models/EventSession');
const User = require('../models/User');
const Registration = require('../models/Registration');

router.get('/financeteam-registration-index', async (req, res) => {
    try {
        const registrations = await Registration.findAll();

        const results = await Promise.all(registrations.map(async (reg) => {
            const user = await User.findByPk(reg.user_id);
            const session = await EventSession.findByPk(reg.event_session_id);

            if (!user || !session) return null;

            const event = await Event.findByPk(session.event_id);
            if (!event) return null;

            return {
                registration_id: reg.id,
                payment_status: reg.payment_status,
                payment_proof: reg.payment_proof,
                user: {
                    name: user.name,
                    email: user.email
                },
                event: {
                    name: event.name,
                    transaction_fee: event.transaction_fee,
                },
                session: {
                    session: session.session,
                }
            };
        }));

        const filteredResults = results.filter(item => item !== null);

        return res.json(filteredResults);
    } catch (error) {
        console.error('Error fetching registration payments:', error);
        return res.status(500).json({ message: 'Internal Server Error' });
    }
});

router.put('/financeteam-registration-update/:id', async (req, res) => {
    const { payment_status } = req.body;

    try {
        const registration = await Registration.findByPk(req.params.id);
        if (!registration) {
            return res.status(404).json({ message: 'Data registrasi tidak ditemukan' });
        }

        registration.payment_status = payment_status;
        await registration.save();

        res.json({ status: 'success', message: 'Status pembayaran berhasil diperbarui' });
    } catch (err) {
        console.error(err);
        res.status(500).json({ status: 'error', message: 'Terjadi kesalahan server' });
    }
});

module.exports = router;