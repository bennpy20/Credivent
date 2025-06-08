require('dotenv').config();

const express = require('express');
const router = express.Router();
const bcrypt = require('bcrypt');
const Event = require('../models/Event');
const EventSession = require('../models/EventSession');
const User = require('../models/User');
const Registration = require('../models/Registration');
const QRCode = require('qrcode');
const path = require('path');
const fs = require('fs');

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

        // Update status pembayaran
        registration.payment_status = payment_status;

        // Jika status pembayaran disetujui, generate QR code
        if (payment_status === 2) {
            const session = await EventSession.findByPk(registration.event_session_id);
            const event = await Event.findByPk(session.event_id);

            const qrPayload = {
                registration_id: registration.id,
                user_id: registration.user_id,
                event_id: event.id,
                event_name: event.name,
                session_id: session.id,
                session_title: session.title,
                valid_from: session.session_start,
                valid_until: session.session_end
            };

            // Tentukan path dan filename
            const qrDir = path.join(__dirname, '../../frontend/public/qrcodes');
            const fileName = `qr-${registration.id}-${Date.now()}.png`;
            const filePath = path.join(qrDir, fileName);

            // Pastikan folder ada
            if (!fs.existsSync(qrDir)) {
                fs.mkdirSync(qrDir, { recursive: true });
            }

            // Generate dan simpan QR code ke file
            await QRCode.toFile(filePath, JSON.stringify(qrPayload));

            // Simpan path relatif ke database (misal: qrcodes/qr-123.png)
            const baseUrl = 'http://127.0.0.1:8000';
            registration.qrcode = `${baseUrl}/qrcodes/${fileName}`;
        }

        await registration.save();

        res.json({ status: 'success', message: 'Status pembayaran berhasil diperbarui (QR code dibuat jika disetujui)' });
    } catch (err) {
        console.error(err);
        res.status(500).json({ status: 'error', message: 'Terjadi kesalahan server' });
    }
});

module.exports = router;