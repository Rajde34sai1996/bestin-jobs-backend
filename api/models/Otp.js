/**
 * Otp.js
 *
 * @description :: A model definition represents a database table/collection.
 * @docs        :: https://sailsjs.com/docs/concepts/models-and-orm/models
 */

module.exports = {
  attributes: {
    verify_by: {
      type: "string",
      required: true,
    },
    otp: {
      type: "number",
      required: true,
    },
    expire_time: {
      type: "number",
      required: true,
    },
    // Disable automatic timestamps
    updatedAt: false,
    createdAt: false,
  },
};

