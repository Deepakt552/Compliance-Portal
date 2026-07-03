# Compliance Application

## Project Overview
The Compliance Application is a Laravel-based system designed to manage and track compliance documents for property management. It allows users to upload required documents for verification while administrators can approve or reject these documents, ensuring regulatory compliance.

## Features

### User Side
- User authentication and profile management
- Dashboard to view family members and required documents
- Document upload and status tracking
- Notifications for document status changes
- Re-upload functionality for rejected documents

### Admin Side
- Admin authentication and dashboard
- View all users and their household/family members
- Filter users by property code and unit number
- Document verification (approve/reject)
- Add comments for rejected documents
- Email notifications to users when document status changes

## System Architecture

### Models

#### User
- Represents a tenant with a unit in a property
- Fields: name, email, password, UserId, UnitNo, FirstName, LastName, Age, FamilySize, CertificationDate, RecertificationDate, ContactDetails, PhoneNumber, Code, Vacant
- Relationships: 
  - Belongs to a Property
  - Has many HouseholdData entries (family members)

#### Admin
- Represents administrative users who manage compliance verification
- Fields: name, email, password
- Separate authentication system from regular users

#### HouseholdData
- Represents family members of a tenant
- Fields: UnitNo, userId, firstName, lastName, AdultOrMinor, Relation, Student, Age, FamilySize, CertificationDate, RecertificationDate, Code, dob, gender
- Relationships:
  - Belongs to a User
  - Has many Documents
  - Belongs to a Property

#### Document
- Represents compliance documents uploaded by users
- Fields: user_id, family_member_id, document_name, file_path, status, document_number, comments
- Document types: 30 predefined document types (tax papers, paystubs, etc.)
- Status options: pending, verified, rejected
- Relationships:
  - Belongs to a User
  - Belongs to a HouseholdData (family member)

#### Properties
- Represents properties managed by the system
- Fields: Code, Property (name)
- Relationships:
  - Has many Users
  - Has many HouseholdData entries

#### Notification
- Represents system notifications for document status changes
- Fields: message, user_id, family_member_id, role, status
- Used to notify users of document approval/rejection

### Controllers

#### UserDashboardController
- Manages user dashboard functionality
- Methods:
  - index(): Displays user dashboard with family members and documents
  - uploadDocument(): Handles document uploads
  - documentStatus(): Shows status of all uploaded documents
  - reuploadDocument(): Handles re-uploading rejected documents

#### AdminDashboardController
- Manages admin dashboard functionality
- Methods:
  - index(): Displays admin dashboard with filtering options
  - showUserDocuments(): Shows documents for a specific user
  - approveDocument(): Approves a document
  - rejectDocument(): Rejects a document with comments

#### UserController
- Manages user account-related functions
- Handles user registration and profile updates

#### PropertyController
- Manages property-related functions
- Handles property creation and updates

#### HouseholdController
- Manages family member data
- Handles adding/editing family members

#### DocumentController
- Dedicated controller for document management
- Handles document retrieval and manipulation

#### Auth Controllers
- Separate authentication controllers for users and admins

## Workflow

1. **User Registration/Login**
   - Users register or login to access the system
   - New users must provide profile information

2. **Family Information**
   - Users add family members to their household
   - System tracks details like age, relationship, student status

3. **Document Upload**
   - Users upload required compliance documents for each family member
   - System categorizes documents by predefined types
   - Documents are stored in the storage system with unique names

4. **Admin Verification**
   - Admins review uploaded documents
   - Admins can approve or reject documents
   - For rejected documents, admins provide comments explaining the reason

5. **Status Updates & Notifications**
   - Users receive email notifications when documents are approved/rejected
   - Users can view document status on their dashboard
   - Users can re-upload rejected documents with corrections

6. **Compliance Tracking**
   - System tracks overall compliance status for each user/household
   - Admins can filter and view compliance by property and unit

## Technical Details

### Middleware
- Authentication middleware for both users and admins
- CheckUserDetailsMiddleware to ensure complete user profiles

### Storage
- Documents stored in the 'documents' directory
- Unique filenames generated for uploaded files

### Mailing
- Email notifications for document status changes
- Customized emails for document approval and rejection

### Database Structure
- Uses migrations for structured database setup
- Relationships maintained through foreign keys
- Tracks document history and status changes

## Setup Instructions

1. Clone the repository
2. Install dependencies with `composer install` and `npm install`
3. Configure environment variables in .env file
4. Run migrations with `php artisan migrate`
5. Start the development server with `php artisan serve`
6. Compile assets with `npm run dev`

## License
[License Information]
