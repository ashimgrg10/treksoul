from flask import Flask, request, jsonify
import joblib
import pandas as pd

# Load model and encoders
bundle = joblib.load("hotel_recommender_model.pkl")
model = bundle["model"]
encoders = bundle["label_encoders"]

app = Flask(__name__)

@app.route("/recommend", methods=["POST"])
def recommend():
    data = request.get_json()
    hotel_type = data.get("hotel_type")
    best_day = int(data.get("day"))

    # Encode input
    encoded_type = encoders["type"].transform([hotel_type])[0]
    X = pd.DataFrame([[encoded_type, best_day]], columns=["type", "best_for_days"])

    # Predict hotel (encoded)
    hotel_encoded = model.predict(X)[0]

    # Decode hotel name
    hotel_name = encoders["hotel_name"].inverse_transform([hotel_encoded])[0]
    return jsonify({"recommended_hotel": hotel_name})

if __name__ == "__main__":
    app.run(debug=True, port=5000)
