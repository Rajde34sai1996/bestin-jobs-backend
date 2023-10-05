/**
 * ApiController
 *
 * @description :: Server-side actions for handling incoming requests.
 * @help        :: See https://sailsjs.com/docs/concepts/actions
 */

const events = require("../services/events");
const mailer = require("../services/mailer");

module.exports = {
  test: async function (req, res) {
    const params = req.allParams();
    return res.ok({
      success: true,
      params,
    });
  },

  testNotification: async function (req, res) {
    const param = req.allParams();
    await events.sendEvent(1, {
      status: param.status,
      type: param.type,
      title: param.title,
      description: param.description,
    });
    return res.ok({
      success: true,
    });
  },

  getUserData: async function (req, res) {
    return res.ok({
      success: true,
      data: req.user,
    });
  },

  verifyEmail: async function (req, res) {
    const params = req.allParams();
    var verify = await Users.count({
      id: params.id,
      token: params.code,
    });
    if (verify) {
      var data = await Users.updateOne({ id: params.id }).set({
        token: null,
        is_email_verified: 1,
      });
      return res.ok({
        message: res.locals.__("Account has been verified successfully."),
        success: true,
        data,
      });
    } else {
      return res.ok({
        message: res.locals.__("Invalid code."),
        success: false,
      });
    }
  },
  test: async function (req, res){
    const done = await Mymodel.find();
    return res.ok({
      success: true,
      done,
    });
  }
};
