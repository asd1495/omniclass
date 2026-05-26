import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import { useAuth } from './hooks/useAuth';
import Login from './pages/auth/Login';
import Register from './pages/auth/Register';
import DashboardLayout from './pages/dashboard/DashboardLayout';
import DashboardOverview from './pages/dashboard/DashboardOverview';
import StudentList from './pages/students/StudentList';
import CourseList from './pages/courses/CourseList';
import SubjectList from './pages/subjects/SubjectList';
import AttendanceTracker from './pages/attendance/AttendanceTracker';
import './App.css';

const PrivateRoute: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const { isAuthenticated, isLoading } = useAuth();
  
  if (isLoading) return <div className="loading-screen">Loading...</div>;
  
  return isAuthenticated ? <>{children}</> : <Navigate to="/login" />;
};

const PlaceholderPage: React.FC<{ title: string }> = ({ title }) => (
  <div className="overview-container">
    <div className="welcome-section">
      <h1>{title}</h1>
      <p>This module is coming soon.</p>
    </div>
    <div className="dashboard-placeholder">
       <div className="placeholder-content">
          <h3>Work in Progress</h3>
          <p>We are currently building the {title.toLowerCase()} management features.</p>
       </div>
    </div>
  </div>
);

function App() {
  return (
    <AuthProvider>
      <Router>
        <Routes>
          {/* Auth Routes */}
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />

          {/* Protected Dashboard Routes */}
          <Route 
            path="/" 
            element={
              <PrivateRoute>
                <DashboardLayout />
              </PrivateRoute>
            }
          >
            <Route index element={<Navigate to="/dashboard" replace />} />
            <Route path="dashboard" element={<DashboardOverview />} />
            <Route path="students" element={<StudentList />} />
            <Route path="courses" element={<CourseList />} />
            <Route path="subjects" element={<SubjectList />} />
            <Route path="attendance" element={<AttendanceTracker />} />
          </Route>

          {/* Catch all */}
          <Route path="*" element={<Navigate to="/dashboard" replace />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
}

export default App;
