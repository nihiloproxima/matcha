# Matcha - A Modern Dating Website

Matcha is a feature-rich dating website built with a custom PHP MVC micro-framework. It helps users find potential matches based on an intelligent matching algorithm that considers location, popularity, and shared interests.

## 🌟 Features

- **Smart Matching Algorithm**: Find potential matches based on:
  - Geographic location
  - Popularity score
  - Common interest tags
- **User Authentication**: Secure login system with Google OAuth support
- **Location-based Services**: Precise geolocation for better matching
- **Profile Management**: Comprehensive user profiles with interests and preferences
- **Real-time Interactions**: Built-in messaging and activity tracking

## 🚀 Getting Started

### Prerequisites

- PHP (with necessary extensions)
- Web server (Apache/Nginx)
- MySQL/MariaDB
- Node.js (for API server)

### Installation

1. Clone the repository
2. Run the setup script:
   ```bash
   sh setup.sh
   ```
3. Configure the database by visiting:
   ```
   http://localhost/config/setup
   ```

### Configuration

1. Add your API keys in:
   - `Models/AdressModel.php`
   - `Models/UserModel.php`

2. Set up Google OAuth:
   - Place your Google credentials in `client_credentials.json`

## 🏗️ Project Structure

- `/Api` - API server and endpoints
- `/Assets` - Static resources
- `/Config` - Configuration files
- `/Controllers` - MVC Controllers
- `/Models` - Data models and business logic
- `/Views` - Frontend templates

## 💻 Technical Stack

- **Backend**: Custom PHP MVC Framework
- **Database**: MySQL/MariaDB
- **API Server**: Node.js
- **Authentication**: Google OAuth
- **Geolocation Services**: Custom implementation

## 🔧 Development

To generate test activity data, uncomment lines 49 and 50 in `Api/server.js`
