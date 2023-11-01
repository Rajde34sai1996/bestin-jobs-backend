const express = require("express");
const bodyParser = require("body-parser");
const cors = require("cors");
const helmet = require("helmet");
const app = express();
var http = require("http");
const { Server } = require("socket.io");
const server = http.createServer(app);

const io = new Server(server, {
  cors: {
    origin: "*",
    methods: ['GET', 'POST', 'PUT', 'DELETE','OPTIONS'],
    allowedHeaders:['Origin', 'X-Requested-With', 'Content-Type', 'Accept', 'Authorization', 'language'],
    credentials: true
  },
});

// app.use(cors(corsOptions));
app.use(helmet());
app.use(cors({
  origin: "*",
  methods: ['GET', 'POST', 'PUT', 'DELETE','OPTIONS'],
  allowedHeaders: ['Content-Type', 'Authorization'],
  credentials: true
}));
app.use(function(req, res, next) {
  res.header("Access-Control-Allow-Origin", "*");
  res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
  next();
});


app.disable("x-powered-by");
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.disable("etag");

app.get("/", (req, res) => {
  res.json({ success: true, message: "The application is running." });
});


app.use((req, res, next) => {
  next(new ApiError(httpStatus.NOT_FOUND, 'Not found'));
});

// set port, listen for requests
const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}.`);
});

module.exports = app
