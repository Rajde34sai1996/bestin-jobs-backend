"use strict";

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.createTable("error_log", {
      id: {
        allowNull: false,
        autoIncrement: true,
        primaryKey: true,
        type: Sequelize.INTEGER,
      },
      createdAt: {
        type: Sequelize.BIGINT,
        allowNull: false,
      },
      updatedAt: {
        type: Sequelize.BIGINT,
        allowNull: false,
      },
      error_name: {
        type: Sequelize.STRING,
        allowNull: true,
      },
      error_message: {
        type: Sequelize.STRING,
        allowNull: true,
      },
      error_path: {
        type: Sequelize.STRING,
        allowNull: true,
      },
      error_folder_path: {
        type: Sequelize.STRING,
        allowNull: true,
      },
      parameters: {
        type: Sequelize.STRING,
        allowNull: true,
      },
    });
  },

  async down(queryInterface, Sequelize) {
    await queryInterface.dropTable("error_log");
  },
};
