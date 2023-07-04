# Base image
FROM python:3.11 as base

# Set up working directory
WORKDIR /app

# Install dependencies
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Local development image
FROM base as local

# Start the Flask development server
CMD [ "flask", "run","--host","0.0.0.0"]

# Production image
FROM base as production

# Copy application code
COPY ./src .

# Set the entrypoint command for local development
ENTRYPOINT ["flask", "run"]