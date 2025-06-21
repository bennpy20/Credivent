require('dotenv').config();

const express = require('express');
const router = express.Router();
const bcrypt = require('bcrypt');
const User = require('../models/User');
const generateUserId = require('../utils/generateUserId');

//// Route untuk Admin kelola Committee
router.get('/admin-committee-index', async (req, res) => {
    const users = await User.findAll({ where: { role: 3 } });
    res.json(users);
});

router.post('/admin-committee-store', async (req, res) => {
    const { email, password, name, role, acc_status } = req.body;

    try {
        const existingUser = await User.findOne({ where: { email } });
        if (existingUser) {
            return res.status(400).json({ status: 'fail', message: 'Email sudah terdaftar' });
        }

        const newUserId = await generateUserId();
        const hashedPassword = await bcrypt.hash(password, 10);

        const newUser = await User.create({
            id: newUserId,
            name,
            email,
            password: hashedPassword,
            role,
            acc_status
        });

        res.json({
            status: 'success',
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

router.get('/admin-committee-edit/:id', async (req, res) => {
    const user = await User.findByPk(req.params.id);
    if (!user) return res.status(404).json({ message: 'User tidak ditemukan' });
    res.json(user);
});

router.put('/admin-committee-update/:id', async (req, res) => {
    const { name, email, acc_status } = req.body;
    try {
        const user = await User.findByPk(req.params.id);
        if (!user) return res.status(404).json({ message: 'User tidak ditemukan' });

        user.name = name;
        user.email = email;
        user.acc_status = acc_status;
        await user.save();

        res.json({ status: 'success', message: 'User berhasil diupdate' });
    } catch (err) {
        console.error(err);
        res.status(500).json({ status: 'error', message: 'Server error' });
    }
});

router.delete('/admin-committee-destroy/:id', async (req, res) => {
    try {
        const user = await User.findByPk(req.params.id);
        if (!user) return res.status(404).json({ message: 'User tidak ditemukan' });

        await user.destroy();
        res.json({ status: 'success', message: 'User berhasil dihapus' });
    } catch (err) {
        console.error(err);
        res.status(500).json({ status: 'error', message: 'Server error' });
    }
});



//// Route untuk Admin kelola Finance Team
router.get('/admin-financeteam-index', async (req, res) => {
    const users = await User.findAll({ where: { role: 4 } });
    res.json(users);
});

router.post('/admin-financeteam-store', async (req, res) => {
    const { email, password, name, role, acc_status } = req.body;

    try {
        const existingUser = await User.findOne({ where: { email } });
        if (existingUser) {
            return res.status(400).json({ status: 'fail', message: 'Email sudah terdaftar' });
        }

        const newUserId = await generateUserId();
        const hashedPassword = await bcrypt.hash(password, 10);

        const newUser = await User.create({
            id: newUserId,
            name,
            email,
            password: hashedPassword,
            role,
            acc_status
        });

        res.json({
            status: 'success',
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

router.get('/admin-financeteam-edit/:id', async (req, res) => {
    const user = await User.findByPk(req.params.id);
    if (!user) return res.status(404).json({ message: 'User tidak ditemukan' });
    res.json(user);
});

router.put('/admin-financeteam-update/:id', async (req, res) => {
    const { name, email, acc_status } = req.body;
    try {
        const user = await User.findByPk(req.params.id);
        if (!user) return res.status(404).json({ message: 'User tidak ditemukan' });

        user.name = name;
        user.email = email;
        user.acc_status = acc_status;
        await user.save();

        res.json({ status: 'success', message: 'User berhasil diupdate' });
    } catch (err) {
        console.error(err);
        res.status(500).json({ status: 'error', message: 'Server error' });
    }
});

router.delete('/admin-financeteam-destroy/:id', async (req, res) => {
    try {
        const user = await User.findByPk(req.params.id);
        if (!user) return res.status(404).json({ message: 'User tidak ditemukan' });

        await user.destroy();
        res.json({ status: 'success', message: 'User berhasil dihapus' });
    } catch (err) {
        console.error(err);
        res.status(500).json({ status: 'error', message: 'Server error' });
    }
});


//// Dashboard Admin
router.get('/admin-dashboard-index', async (req, res) => {
    try {
        const panitiaCount = await User.count({ where: { role: 3 } });
        const keuanganCount = await User.count({ where: { role: 4 } });

        const users = await User.findAll({ where: { role: [3, 4] } } );

        res.json({
            panitia: panitiaCount,
            keuangan: keuanganCount,
            users: users
        });
    } catch (error) {
        console.error('Error di dashboard admin:', error);
        res.status(500).json({ error: 'Server error' });
    }
});

module.exports = router;