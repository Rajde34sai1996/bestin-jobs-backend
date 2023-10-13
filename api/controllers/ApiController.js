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
    try {
      let { user_id, allowAll, jobAlerts, jobApplication, appUpdates } =
        req.body;
      let findUser = await Users.findOne({ id: user_id });
      if(!findUser) {
        return res.json({ succes: false, message: "User not found!" });
      }
      let setting = JSON.parse(findUser.setting)
      if(allowAll){
        setting.allowAll = true
        setting.jobAlerts = true
        setting.jobApplication = true
        setting.appUpdates = true
      } else if (jobAlerts) {
        setting.jobAlerts = true
      } else if (jobApplication) {
        setting.jobApplication = true
      } else if (appUpdates) {
        setting.appUpdates = true
      }
      let createNotification = await Notification.create({
        sender_id: 1,
        receiver_id: findUser.id,
        data: JSON.stringify(setting),
      }).fetch()

      let updateUser = await Users.updateOne({ id: user_id }).set({
        setting: JSON.stringify(setting),
      });

      return res.json({ status: true, message: "Notification send!" });
    } catch (error) {
      await general.errorLog(error, "ApiController/testNotification");
      return res.json({
        success: false,
        message: "Somethinng want wrong!",
      });
    }
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
  test: async function (req, res) {
    const done = await Mymodel.find();
    return res.ok({
      success: true,
      done,
    });
  },
  upload: function(req, res) {
    // Access uploaded file details using req.file
    var uploadedFile = req.file;
    console.log("uploadedFile", uploadedFile);

    // Do something with the uploaded file, e.g., save it to a database or return a response
    return res.json({ message: 'File uploaded successfully', file: uploadedFile });
  }
  
};
  
