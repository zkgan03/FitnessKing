const express = require("express");
const svgCaptcha = require("svg-captcha");
const app = express();
const port = 3001;

// Middleware to handle JSON requests
app.use(express.json());

// Endpoint to generate CAPTCHA
app.get("/generate-captcha", (req, res) => {
	const captcha = svgCaptcha.create({
		background: "#ffffff", // Set background color to white
		color: true, // Enable text color
		noise: 2, // Add noise for better security
		size: 6, // Number of characters
		width: 200, // Width of the image
		height: 80, // Height of the image
	});

	const captchaText = captcha.text; // Store the CAPTCHA text
	const captchaSvg = captcha.data; // Get the SVG image

	// Return the CAPTCHA in JSON format
	res.json({
		captchaText: captchaText,
		captchaSvg: captchaSvg,
	});
});

// Endpoint to verify CAPTCHA
app.post("/verify-captcha", (req, res) => {
	const { inputText, captchaText } = req.body;

	if (inputText === captchaText) {
		return res.json({ success: true, message: "Captcha verified successfully." });
	}
	res.json({ success: false, message: "Captcha verification failed." });
});

app.listen(port, () => {
	console.log(`Captcha service running on http://localhost:${port}`);
});
