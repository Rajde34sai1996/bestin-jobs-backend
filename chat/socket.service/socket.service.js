const db = require("../models");
const { getUser } = require("./general.service");
const {
  listChatGroup,
  senderChatlist,
  logout,
  findSocketID,
} = require("./chat.service");
const _ = require("lodash");
const generalService = require("./general.service");
module.exports = {
  chatSocket: async (io) => {
    const rooms = {};
    let obj = {};
    io.on("connection", (socket) => {
      console.log("socket", socket.id);
      socket.on("userconnect", async (req, res) => {
        try {
          const { userId } = req;
          if (!_.isNull(userId) && !_.isUndefined(userId)) {
            obj = {
              user_id: userId,
              token: socket.id,
            };
            await db.Soket_tokens.create(obj);
            let result = await listChatGroup(req);
            if (result.success == true) {
              res(result);
            }else{
              res({
                success: true,
                data:{},
              });
            }
          } else {
            res({
              status: false,
              message: "error while fetching chat",
            });
          }
        } catch (error) {
          generalService.errorLog(error, "socket.userconnect/userconnect");
          res({
            status: false,
          });
        }
      });
      socket.on("singleChat", async function (req, res) {
        try {
          if (!_.isNull(req)) {
            let result = await senderChatlist(req);
            if (result.success) {
              res(result);
            }
          }
          res({
            status: false,
            message: "error while fetching chat",
          });
        } catch (error) {
          generalService.errorLog(error, "socket.singleChat/singleChat");
          res({
            status: false,
          });
        }
      });
      socket.on("logout", async (req, res) => {
        try {
          const { userId } = req;
          if (userId) {
            const result = await logout(userId, socket.id);
            if (result.success) {
              res(result);
              socket.disconnect();
            }
          }
          res({
            status: false,
            message: "error while logging out",
          });
        } catch (error) {
          generalService.errorLog(error, "socket.logout/logout");
          res({
            status: false,
          });
        }
      });
      socket.on("chat_message", async function (data, callBackFn) {
        try {
          if (data.group_id && data.sender_id && data.message) {
            let obj = {
              group_id: data.group_id,
              sender_id: data.sender_id,
              message: data.message,
            };
            await db.Project_group_chats.create(obj);
            const findID = await findSocketID(data.receiver_id);
            if (findID) {
              io.to(findID).emit("receiveChat", data);
              callBackFn({ success: true });
            }
            callBackFn({
              status: true,
            });
          }
          callBackFn({
            status: false,
            message: "error while fetching chat",
          });
        } catch (error) {
          generalService.errorLog(error, "socket.chat_message/chat_message");
          callBackFn({
            status: false,
          });
        }
      });
      socket.on("isTyping", async (req, res) => {
        try {
          const { group_id, receiver_id, sender_id } = req;
          if (group_id && receiver_id && sender_id) {
            const data = {
              text: "Typing...",
              sender_id: sender_id,
              group_id: group_id,
              receiver_id: receiver_id,
            };
            const findID = await findSocketID(receiver_id);
            if (findID) {
              io.to(findID).emit("receiveTyping", data);
            }
          }
          res({ status: false, message: "data not found" });
        } catch (error) {
          generalService.errorLog(error, "socket.isTyping/isTyping");
          res({ status: false });
        }
      });
      socket.on("stopTyping", async (req, res) => {
        try {
          const { group_id, receiver_id, sender_id } = req;
          if (group_id && receiver_id && sender_id) {
            const data = {
              text: "",
              sender_id: sender_id,
              group_id: group_id,
              receiver_id: receiver_id,
            };

            const findID = await findSocketID(receiver_id);
            if (findID) {
              io.to(findID).emit("receiveTyping", data);
              res({
                status: true,
                message: "stopTyping successfully.",
                code: 200,
              });
            }
          }
          res({ status: false, message: "data not found" });
        } catch (error) {
          generalService.errorLog(error, "socket.stopTyping/stopTyping");
          res({ status: false });
        }
      });
    });
  },
};

// socket.on("join_room", async ({ username, room }) => {
//   let userId = username;
//   socket.join(room);
//   if (!rooms[room]) {
//     rooms[room] = [];
//   }
//   rooms[room].push({ id: socket.id, username });
//   let obj = {
//     user_id: userId,
//     token: socket.id,
//   };
//   io.to(room).emit("room_users", rooms[room]);
//   await db.Soket_tokens.create(obj);
// });

// socket.on("chat_message", async ({ group_id, message, username }) => {
//   let obj = { group_id: group_id, sender_id: username, message };
//   io.to(group_id).emit("chat_message", { id: obj.userId, message });
//   await db.Project_group_chats.create(obj);
// });
