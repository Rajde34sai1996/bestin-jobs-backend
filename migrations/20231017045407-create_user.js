'use strict';
/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.createTable('users', {
      id: {
        allowNull: false,
        autoIncrement: true,
        primaryKey: true,
        type: Sequelize.INTEGER
      },
      email: {
        type: Sequelize.STRING,
        allowNull: false
      },
      password: {
        type: Sequelize.STRING,
        allowNull: true
      },
      name: {
        type: Sequelize.STRING,
        allowNull: false
      },
      dob: {
        type: Sequelize.STRING(20),
        allowNull: false
      },
      gender: {
        type: Sequelize.ENUM('male', 'female', 'other'),
        allowNull: false
      },
      phone_number: {
        type: Sequelize.STRING,
        allowNull: false
      },
      country: {
        type: Sequelize.STRING(20),
        allowNull: false
      },
      role: {
        type: Sequelize.ENUM('admin', 'user'),
        allowNull: false
      },
      setting: {
        type: Sequelize.JSON,
        allowNull: true
      },
      profile_pic: {
        type: Sequelize.STRING,
        allowNull: true
      },
      createdAt: {
        type: Sequelize.BIGINT,
        allowNull: false,
        defaultValue: () => Date.now(),
      },
      updatedAt: {
        type: Sequelize.BIGINT,
        allowNull: false,
        defaultValue: () => Date.now(),
      }
    });
  },
  async down(queryInterface, Sequelize) {
    await queryInterface.dropTable('users');
  }
};