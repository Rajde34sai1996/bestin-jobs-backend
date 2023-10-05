module.exports = {
  friendlyName: "Connect Socket",

  description: "Connect Socket User.",

  inputs: {},

  exits: {},

  fn: async function (inputs, exits) {
    if (this.req.isSocket) {
      sails.log.info("Socket ID:", this.req.isSocket);
      const userDetail = this.req.user;
      await User_sockets.destroy({
        user_id: userDetail.id,
      });
      var userRecord = await User_sockets.create({
        user_id: userDetail.id,
        socket_id: this.req.socket.id,
      }).fetch();
      if (!userRecord) {
        return exits.invalid({
          message: "invalid",
        });
      }
      await sails.sockets.join(this.req, `Qurp_${userDetail.id}`);
      return exits.success({
        message: this.res.locals.__("Socket connected successfully"),
        data: userRecord,
        success: true,
      });
    }
    return true;
  },
};
