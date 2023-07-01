# Use an official Python runtime as the base image
FROM python:3.11 as base

# Set the working directory in the container
WORKDIR /app

# Copy the requirements file into the container
COPY requirements.txt .

# Install any needed packages specified in requirements.txt
RUN pip install --no-cache-dir -r requirements.txt

# Copy the rest of the application's code into the container
COPY src .

# Local development stage
FROM base as local
# Define the default command to run when the container starts
ENTRYPOINT ["tail", "-f", "/dev/null"]

FROM base as production
CMD ["python", "app.py"]
