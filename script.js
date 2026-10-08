/* =========================================================
   NOTENEST - PHP VERSION
   frontend/js/script.js

   PHP backend:
   ../backend/
   ========================================================= */

const API = "../backend";

/* =========================================================
   HELPER
   ========================================================= */

async function getJSONResponse(response) {
    const text = await response.text();

    console.log("SERVER RESPONSE:", text);

    try {
        return JSON.parse(text);
    } catch (error) {
        console.error("INVALID JSON:", text);
        throw new Error("Server returned an invalid response.");
    }
}

/* =========================================================
   REGISTER
   ========================================================= */

const registerForm = document.getElementById("registerForm");

if (registerForm) {

    registerForm.addEventListener("submit", async function (event) {

        event.preventDefault();

        const full_name =
            document.getElementById("registerName")?.value.trim() || "";

        const username =
            document.getElementById("registerUsername")?.value.trim() || "";

        const email =
            document.getElementById("registerEmail")?.value.trim() || "";

        const password =
            document.getElementById("registerPassword")?.value || "";

        const course =
            document.getElementById("registerCourse")?.value.trim() || "";

        const year_level =
            document.getElementById("registerYear")?.value || "";

        /* Basic validation */

        if (!full_name || !username || !email || !password) {
            alert("Please complete all required fields.");
            return;
        }

        const formData = new FormData();

        formData.append("full_name", full_name);
        formData.append("username", username);
        formData.append("email", email);
        formData.append("password", password);
        formData.append("course", course);
        formData.append("year_level", year_level);

        try {

            const response = await fetch(
                API + "/register.php",
                {
                    method: "POST",
                    body: formData
                }
            );

            const data = await getJSONResponse(response);

            if (!data.success) {
                alert(data.message || "Registration failed.");
                return;
            }

            alert(
                "Account created successfully! You can now log in."
            );

            registerForm.reset();

            /*
               If your register/login are on the same page,
               use showLogin().
            */

            if (typeof showLogin === "function") {
                showLogin();
            } else {
                window.location.href = "login.html";
            }

        } catch (error) {

            console.error("REGISTER ERROR:", error);

            alert(
                error.message ||
                "Unable to connect to NoteNest."
            );
        }
    });
}

/* =========================================================
   STUDENT LOGIN
   ========================================================= */

const loginForm = document.getElementById("loginForm");

if (loginForm) {

    loginForm.addEventListener("submit", async function (event) {

        event.preventDefault();

        const email =
            document.getElementById("loginEmail")?.value.trim() || "";

        const password =
            document.getElementById("loginPassword")?.value || "";

        if (!email || !password) {
            alert("Please enter your email and password.");
            return;
        }

        const formData = new FormData();

        formData.append("email", email);
        formData.append("password", password);

        try {

            const response = await fetch(
                API + "/login.php",
                {
                    method: "POST",
                    body: formData,
                    credentials: "include"
                }
            );

            const data = await getJSONResponse(response);

            if (!data.success) {
                alert(data.message || "Login failed.");
                return;
            }

            /* Save logged-in user locally */

            if (data.user) {
                localStorage.setItem(
                    "currentUser",
                    JSON.stringify(data.user)
                );
            }

            /*
               For pages using the old single-page app
            */

            const authPage =
                document.getElementById("authPage");

            const adminApp =
                document.getElementById("adminApp");

            const studentApp =
                document.getElementById("studentApp");

            if (authPage) {
                authPage.classList.add("hidden");
            }

            if (adminApp) {
                adminApp.classList.add("hidden");
            }

            if (studentApp) {
                studentApp.classList.remove("hidden");
            }

            /*
               For separate HTML pages
            */

            if (typeof showStudentPage === "function") {

                showStudentPage("dashboard");

            } else {

                window.location.href = "index.html";

            }

        } catch (error) {

            console.error("LOGIN ERROR:", error);

            alert(
                error.message ||
                "Unable to connect to NoteNest."
            );
        }
    });
}

/* =========================================================
   UPLOAD RESOURCE
   ========================================================= */

const uploadForm =
    document.getElementById("uploadForm");

if (uploadForm) {

    uploadForm.addEventListener(
        "submit",
        async function (event) {

            event.preventDefault();

            const formData =
                new FormData(uploadForm);

            const uploadMessage =
                document.getElementById("uploadMessage");

            if (uploadMessage) {
                uploadMessage.textContent = "Uploading...";
                uploadMessage.className =
                    "upload-message";
            }

            try {

                const response = await fetch(
                    API + "/upload_notes.php",
                    {
                        method: "POST",
                        body: formData,
                        credentials: "include"
                    }
                );

                const data =
                    await getJSONResponse(response);

                if (data.success) {

                    if (uploadMessage) {
                        uploadMessage.textContent =
                            data.message ||
                            "Resource uploaded successfully!";

                        uploadMessage.className =
                            "upload-message success";
                    } else {
                        alert(
                            data.message ||
                            "Resource uploaded successfully!"
                        );
                    }

                    uploadForm.reset();

                    setTimeout(function () {

                        window.location.href =
                            "resources.html";

                    }, 1000);

                } else {

                    if (uploadMessage) {

                        uploadMessage.textContent =
                            data.message ||
                            "Unable to upload resource.";

                        uploadMessage.className =
                            "upload-message error";

                    } else {

                        alert(
                            data.message ||
                            "Unable to upload resource."
                        );
                    }
                }

            } catch (error) {

                console.error(
                    "UPLOAD ERROR:",
                    error
                );

                if (uploadMessage) {

                    uploadMessage.textContent =
                        error.message ||
                        "Unable to connect to NoteNest.";

                    uploadMessage.className =
                        "upload-message error";

                } else {

                    alert(
                        error.message ||
                        "Unable to connect to NoteNest."
                    );
                }
            }
        }
    );
}

/* =========================================================
   LOGOUT
   ========================================================= */

const logoutBtn =
    document.getElementById("logoutBtn");

if (logoutBtn) {

    logoutBtn.addEventListener(
        "click",
        async function () {

            try {

                await fetch(
                    API + "/logout.php",
                    {
                        method: "GET",
                        credentials: "include"
                    }
                );

            } catch (error) {

                console.error(
                    "LOGOUT ERROR:",
                    error
                );
            }

            /* Clear browser data */

            localStorage.removeItem(
                "currentUser"
            );

            localStorage.removeItem(
                "notenest_name"
            );

            localStorage.removeItem(
                "notenest_course"
            );

            /* Return to login */

            window.location.href =
                "login.html";
        }
    );
}