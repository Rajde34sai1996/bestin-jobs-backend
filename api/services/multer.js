// // api/services/upload.js

// const multer = require("multer");
// const path = require("path");

// const storage = multer.diskStorage({
//   destination: (req, file, cb) => {
//     cb(null, path.resolve(sails.config.appPath, ".tmp/uploads"));
//   },
//   filename: (req, file, cb) => {
//     const fileName = file.originalname;
//     cb(null, fileName);
//   },
// });

// const upload = multer({
//   storage: storage,
//   limits: { fileSize: 10000000 }, // 10MB limit
// });

// // api/services/upload.js
// module.exports = {
//   uploadFile: async function (file) {
//     return new Promise((resolve, reject) => {
//       file.upload(
//         {
//           dirname: path.resolve(sails.config.appPath, "assets/images"),
//           saveAs: function (file, cb) {
//             const fileName = file.filename;
//             cb(null, fileName);
//           },
//           maxBytes: 10000000,
//         },
//         async (err, uploadedFiles) => {
//           if (err) {
//             reject(err);
//           } else {
//             const uploadedFile = uploadedFiles[0];
//             const imagePath = path.join(
//               sails.config.appPath,
//               "assets/images",
//               uploadedFile.filename
//             );

//             try {
//               resolve({
//                 uploadedFile,
//                 imagePath,
//               });
//             } catch (err) {
//               reject(err);
//             }
//           }
//         }
//       );
//     });
//   },
// };


