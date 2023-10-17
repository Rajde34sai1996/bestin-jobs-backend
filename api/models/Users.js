/**
 * Users.js
 *
 * @description :: A model definition represents a database table/collection.
 * @docs        :: https://sailsjs.com/docs/concepts/models-and-orm/models
 */
const bcrypt = require("bcryptjs");
module.exports = {
  fetchRecordsOnUpdate: true,
  attributes: {
    email: {
      type: "string",
      required: true,
      isEmail: true,
    },
    password: {
      type: "string",
      protect: true,
      allowNull: true,
    },
    name: {
      type: "string",
      allowNull: false,
    },
    dob: {
      type: "string",
      allowNull: false,
    },
    gender: {
      type: "string",
      isIn: ["male", "female", "other"],
      allowNull: false,
    },
    phone_number: {
      type: "string",
      allowNull: false,
    },
    country: {
      type: "string",
      allowNull: false,
    },
    role: {
      type: "string",
      isIn: ["admin", "user"],
      allowNull: false,
    },
    setting: {
      type: "json",
    },
    profile_pic: {
      type: "string",
      allowNull: true,
    },
  },
};
