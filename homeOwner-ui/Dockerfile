# Use Node.js 18 as the base image
FROM node:18

# Set working directory
WORKDIR /app

# Copy package.json and install dependencies
COPY package*.json ./
RUN npm install

# Copy the entire project (excluding files in .dockerignore)
COPY . .

# Expose Vite's default port for hot reloading
EXPOSE 5173

# Start the Vite development server
CMD ["npm", "run", "dev"]
