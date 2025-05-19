require('dotenv').config();

const express = require('express');
const router = express.Router();
const jwt = require('jsonwebtoken');
const bcrypt = require('bcrypt');
const User = require('../models/User');
const Member = require('../models/Member');

const JWT_SECRET = process.env.JWT_SECRET;

// Route untuk register
router.post('/register', async (req, res) => {
    const { email, password, role, name, phone_number, major } = req.body;

    try {
        // Cek apakah email sudah ada
        const existingUser = await User.findOne({ where: { email } });
        if (existingUser) {
            return res.status(400).json({ status: 'fail', message: 'Email sudah terdaftar' });
        }

        //// Untuk tabel User
        // Cari ID terakhir dan generate ID baru
        const lastUser = await User.findOne({
            order: [['id', 'DESC']]
        });

        let newIdUser = 1;
        if (lastUser) {
            // Ambil angka dari ID terakhir, misalnya "M-012" -> 12
            const lastIdUser = parseInt(lastUser.id.split('-')[1]);
            newIdUser = lastIdUser + 1;
        }

        // Format ID baru jadi "M-XXX"
        const newUserId = `U-${newIdUser.toString().padStart(3, '0')}`;

        // Hash password
        const hashedPassword = await bcrypt.hash(password, 10);

        // Simpan user baru dengan ID yang sudah diformat
        const newUser = await User.create({
            id: newUserId,
            email,
            password: hashedPassword,
            role
        });

        //// Untuk tabel Member
        // Cari ID terakhir dan generate ID baru
        const lastMember = await Member.findOne({
            order: [['id', 'DESC']]
        });

        let newIdMember = 1;
        if (lastMember) {
            // Ambil angka dari ID terakhir, misalnya "M-012" -> 12
            const lastIdMember = parseInt(lastMember.id.split('-')[1]);
            newIdMember = lastIdMember + 1;
        }

        // Format ID baru jadi "M-XXX"
        const newMemberId = `M-${newIdMember.toString().padStart(3, '0')}`;

        // Simpan user baru dengan ID yang sudah diformat
        const newMember = await Member.create({
            id: newMemberId,
            name,
            phone_number,
            major,
            user_id: newUserId,
        });

        // Buat token JWT
        const token = jwt.sign(
            { id: newUser.id, email: newUser.email },
            JWT_SECRET,
            { expiresIn: '1h' }
        );

        res.json({
            status: 'success',
            token,
            user: {
                id: newUser.id,
                email: newUser.email
            }
        });

    } catch (error) {
        console.error(error);
        res.status(500).json({ status: 'error', message: 'Server error' });
    }
});

// Route untuk login
router.post('/login', async (req, res) => {
    // console.log("Login request body:", req.body);
    const { email, password } = req.body;

    try {
        const user = await User.findOne({ where: { email } });

        if (!user) {
            return res.status(401).json({ status: 'fail', message: 'Email atau password salah' });
        }

        const passwordMatch = await bcrypt.compare(password, user.password);
        if (!passwordMatch) {
            return res.status(401).json({ status: 'fail', message: 'Email atau password salah' });
        }

        const token = jwt.sign(
            { id: user.id, email: user.email, role: user.role },
            JWT_SECRET,
            { expiresIn: '1h' }
        );

        res.json({
            status: 'success',
            token,
            user: {
                id: user.id,
                email: user.email,
                role: user.role
            }
        });

    } catch (error) {
        console.error(error);
        res.status(500).json({ status: 'error', message: 'Server error' });
    }
});

module.exports = router;