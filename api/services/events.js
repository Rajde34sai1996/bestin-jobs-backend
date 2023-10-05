module.exports = {
  /**Common function for send event */
  sendEvent: async function (id, data) {
    await sails.sockets.broadcast(`Qurp_${id}`, "notification", data);
  },
};
