# Base image
FROM python:3.11 as base

# Set up working directory
WORKDIR /app

# Install dependencies
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy application code
COPY ./src .

# Set the entrypoint command for local development
ENTRYPOINT ["python", "-m", "flask", "run"]

# Local development image
FROM base as local

# Install additional development dependencies
RUN pip install --no-cache-dir flask_debugtoolbar

# Production image
FROM base as production
