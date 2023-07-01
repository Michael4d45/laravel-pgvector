import os
from flask import Flask, jsonify, request
from sentence_transformers import SentenceTransformer

app = Flask(__name__)
model = SentenceTransformer('all-MiniLM-L6-v2')

@app.route('/encode', methods=['POST'])
def encode_text():
    text = request.json['text']
    encoding = model.encode([text])

    return jsonify({'encoding': encoding.tolist()})

if __name__ == '__main__':
    port = int(os.environ.get('PORT', 5000))
    app.run(host='0.0.0.0', port=port)
