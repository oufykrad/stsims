<template>
    <Head title="Login" />
    <div class="auth-page-wrapper pt-5">
        <div class="auth-one-bg-position auth-one-bg">
            <div class="bg-overlay"></div>
            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>
        <div class="auth-page-content">
            <b-container>
                <b-row>
                <b-col lg="12" class="mt-n3">
                    <div class="text-center mt-sm-5 mb-4 text-white-50">
                    <div>
                        <router-link to="/" class="d-inline-block auth-logo">
                        <img class="avatar-md" src="/images/sei.png" alt="" height="20" />
                        </router-link>
                    </div>
                    <p class="mt-3 fs-15 fw-medium">
                       Science Education Institute
                    </p>
                    </div>
                </b-col>
                </b-row>

                <b-row class="justify-content-center">
                <b-col md="8" lg="6" xl="5">
                    <b-card no-body class="mt-4">
                        <b-card-body class="p-4">
                            <a class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <img :src="currentUrl+'/images/avatars/'+user.data.avatar" alt="" class="avatar-sm rounded-circle img-thumbnail">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fs-14 mb-0">{{user.data.firstname}} {{user.data.lastname}}</h6>
                                    <p class="text-muted mb-0">{{user.data.role}}</p>
                                </div>
                            </a>
                            <div id="leadDiscovered3" class="collapse border-top border-top-dashed show" is-nav="false">
                                <div class="p-2 mt-2">
                                    <form class="customform">
                                        <div class="mb-3">
                                            <label class="form-label" for="password-input">Password</label>
                                            <div class="position-relative auth-pass-inputgroup">
                                                <input v-model="password" type="password" class="form-control pe-5 password-input"
                                                    onpaste="return false" placeholder="Enter password"
                                                    id="password-input" aria-describedby="passwordInput"
                                                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                                                <b-button variant="link"
                                                    class="position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                    type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i>
                                                </b-button>
                                            </div>
                                            <div id="passwordInput" class="form-text">Must be at least 8 characters.
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="confirm-password-input">Confirm
                                                Password</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input v-model="password_confirmation" type="password" class="form-control pe-5 password-input"
                                                    onpaste="return false" placeholder="Confirm password"
                                                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                                    id="confirm-password-input" required>
                                                <b-button variant="link"
                                                    class="position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                    type="button" id="confirm-password-input"><i class="ri-eye-fill align-middle"></i>
                                                </b-button>
                                            </div>
                                        </div>

                                        <div id="password-contain" class="p-3 bg-light mb-2 rounded">
                                            <h5 class="fs-13">Password must contain:</h5>
                                            <p id="pass-length" class="invalid fs-12 mb-2">Minimum <b>8 characters</b>
                                            </p>
                                            <p id="pass-lower" class="invalid fs-12 mb-2">At <b>lowercase</b> letter
                                                (a-z)</p>
                                            <p id="pass-upper" class="invalid fs-12 mb-2">At least <b>uppercase</b>
                                                letter (A-Z)</p>
                                            <p id="pass-number" class="invalid fs-12 mb-0">A least <b>number</b> (0-9)
                                            </p>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="auth-remember-check">
                                            <label class="form-check-label" for="auth-remember-check">Remember
                                                me</label>
                                        </div>

                                        <div class="mt-4">
                                            <b-button variant="success" class="w-100" @click="validatepassword" type="submit">Reset Password</b-button>
                                        </div>

                                    </form>
                                </div>
                            </div>

                        </b-card-body>
                    </b-card>

                    <div class="mt-4 text-center">
                    <p class="mb-0">
                        Don't have an account ?
                        <Link to="/register" class="fw-semibold text-primary
                        text-decoration-underline">
                        Signup
                        </Link>
                    </p>
                    </div>
                </b-col>
                </b-row>
            </b-container>
        </div>

      
  </div>
</template>
<script>
export default {
    layout: null,
    props: ['user'],
    data(){
        return {
            currentUrl: window.location.origin,
            email: this.user.data.email,
            password: '',
            password_confirmation: ''
        }
    },
    methods: {
        validatePassword() {
            // passowrd match
            var password = document.getElementById("password-input"),
                confirm_password = document.getElementById("confirm-password-input");

            //Password validation

            if (password.value != confirm_password.value) {
                confirm_password.setCustomValidity("Passwords Don't Match");
            } else {
                confirm_password.setCustomValidity("");
            }
        }
    },
    mounted() {
        var password = document.getElementById("password-input");
        password.onchange = this.validatePassword;
        document.querySelectorAll("form .auth-pass-inputgroup")
            .forEach(function (item) {
                item.querySelectorAll(".password-addon").forEach(function (subitem) {
                    subitem.addEventListener("click", function () {
                        var passwordInput = item.querySelector(".password-input");
                        if (passwordInput.type === "password") {
                            passwordInput.type = "text";
                        } else {
                            passwordInput.type = "password";
                        }
                    });
                });
            });



        var myInput = document.getElementById("password-input");
        var letter = document.getElementById("pass-lower");
        var capital = document.getElementById("pass-upper");
        var number = document.getElementById("pass-number");
        var length = document.getElementById("pass-length");

        // When the user clicks on the password field, show the message box
        myInput.onfocus = function () {
            document.getElementById("password-contain").style.display = "block";
        };

        // When the user clicks outside of the password field, hide the password-contain box
        myInput.onblur = function () {
            document.getElementById("password-contain").style.display = "none";
        };

        // When the user starts to type something inside the password field
        myInput.onkeyup = function () {
            // Validate lowercase letters
            var lowerCaseLetters = /[a-z]/g;
            if (myInput.value.match(lowerCaseLetters)) {
                letter.classList.remove("invalid");
                letter.classList.add("valid");
            } else {
                letter.classList.remove("valid");
                letter.classList.add("invalid");
            }

            // Validate capital letters
            var upperCaseLetters = /[A-Z]/g;
            if (myInput.value.match(upperCaseLetters)) {
                capital.classList.remove("invalid");
                capital.classList.add("valid");
            } else {
                capital.classList.remove("valid");
                capital.classList.add("invalid");
            }

            // Validate numbers
            var numbers = /[0-9]/g;
            if (myInput.value.match(numbers)) {
                number.classList.remove("invalid");
                number.classList.add("valid");
            } else {
                number.classList.remove("valid");
                number.classList.add("invalid");
            }

            // Validate length
            if (myInput.value.length >= 8) {
                length.classList.remove("invalid");
                length.classList.add("valid");
            } else {
                length.classList.remove("valid");
                length.classList.add("invalid");
            }
        };
    },
}
</script>
