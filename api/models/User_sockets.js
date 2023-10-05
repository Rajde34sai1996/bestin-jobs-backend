/**
 * User_sockets.js
 *
 * @description :: A model definition represents a database table/collection.
 * @docs        :: https://sailsjs.com/docs/concepts/models-and-orm/models
 */

module.exports = {
  attributes: {
    user_id: {
      type: "number",
      required: true,
    },
    socket_id: {
      type: "string",
      required: true,
    },
  },
};
