/**
 * More_about_user.js
 *
 * @description :: A model definition represents a database table/collection.
 * @docs        :: https://sailsjs.com/docs/concepts/models-and-orm/models
 */

module.exports = {
  attributes: {
    user_id: {
      type: "number",
      allowNull: false,
    },
    experience_level: {
      type: "string",
      allowNull: false,
      isIn: ["fresher", "experienced"],
    },
    experience_month: {
      type: "string",
      allowNull: true
    },
    experience_year: {
      type: "string",
      allowNull: true
    },
    wroking_time: {
      type: "string",
      allowNull: false
    },
    location: {
      type: "string",
      allowNull: false
    },
    travelling_km: {
      type: "string",
      allowNull: false
    },
    lat: {
      type: "number"
    },
    log: {
      type: "number"
    }
  },
};
