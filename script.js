const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");

sign_up_btn.addEventListener("click", () => {
container.classList.add("sign-up-mode");
});

sign_in_btn.addEventListener("click", () => {
container.classList.remove("sign-up-mode");
});

function validateEmail(email) {
const emailError = document.getElementById("emailError");
const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
if (!regex.test(email.value)) {
    emailError.textContent = "Please enter a valid email address with '@' symbol.";
} else {
    emailError.textContent = "";
}
}
const passwordField = document.getElementById('password');
const toggler = document.getElementById('toggler');