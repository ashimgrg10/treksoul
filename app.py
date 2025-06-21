from flask import Flask, render_template, request, redirect, url_for, session

app = Flask(__name__)
app.secret_key = 'your_secret_key'  # Replace with a secure random key

# In-memory user store: { email: {name: ..., password: ...} }
users = {}

@app.route("/")
def index():
    return render_template("index.html")

@app.route("/register", methods=["GET", "POST"])
def register():
    if request.method == "POST":
        name = request.form.get("name")
        email = request.form.get("email")
        password = request.form.get("password")

        if email in users:
            return "User already exists!", 400

        users[email] = {"name": name, "password": password}
        return redirect(url_for("login"))
    return render_template("register.html")

@app.route("/login", methods=["GET", "POST"])
def login():
    if request.method == "POST":
        email = request.form.get("email")
        password = request.form.get("password")

        user = users.get(email)
        if user and user["password"] == password:
            session["user"] = email
            return redirect(url_for("index"))
        else:
            return "Invalid credentials", 401
    return render_template("login.html")

@app.route("/logout")
def logout():
    session.pop("user", None)
    return redirect(url_for("login"))

@app.route("/treks")
def treks():
    if "user" not in session:
        return redirect(url_for("login"))
    return render_template("treks.html")

if __name__ == "__main__":
    app.run(debug=True)
