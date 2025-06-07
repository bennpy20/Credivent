require('dotenv').config();

const express = require('express');
const router = express.Router();

const authRoutes = require('./auth');
const adminRoutes = require('./admin');
const committeeRoutes = require('./committee');
const financeteamRoutes = require('./financeteam');
const memberRoutes = require('./member');

router.use('/auth', authRoutes);
router.use('/admin', adminRoutes);
router.use('/committee', committeeRoutes);
router.use('/member', memberRoutes);
router.use('/financeteam', financeteamRoutes);

module.exports = router;