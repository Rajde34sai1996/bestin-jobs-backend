/**
 * Notification.js
 *
 * @description :: A model definition represents a database table/collection.
 * @docs        :: https://sailsjs.com/docs/concepts/models-and-orm/models
 */

module.exports = {

  attributes: {
    sender_id: {
      type: "number",
      allowNull: false
    },
    receiver_id: {
      type: "number",
      allowNull: false
    },
    data: {
      type: "string",
      allowNull: false
    },
    is_read: {
      type: "string",
      defaultsTo: '0',
    }
  },

};

