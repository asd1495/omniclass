import React from 'react';
import { Users, BookOpen, GraduationCap, CheckSquare } from 'lucide-react';
import './DashboardOverview.css';

const StatCard: React.FC<{ title: string; value: string; icon: any; color: string }> = ({ title, value, icon: Icon, color }) => (
  <div className="stat-card">
    <div className={`stat-icon ${color}`}>
      <Icon size={24} />
    </div>
    <div className="stat-content">
      <h3>{title}</h3>
      <p>{value}</p>
    </div>
  </div>
);

const DashboardOverview: React.FC = () => {
  return (
    <div className="overview-container">
      <div className="welcome-section">
        <h1>Overview</h1>
        <p>A quick summary of your school's current status.</p>
      </div>

      <div className="stats-grid">
        <StatCard title="Total Students" value="0" icon={Users} color="blue" />
        <StatCard title="Total Courses" value="0" icon={BookOpen} color="indigo" />
        <StatCard title="Total Subjects" value="0" icon={CheckSquare} color="green" />
        <StatCard title="Attendance Rate" value="0%" icon={GraduationCap} color="purple" />
      </div>

      <div className="dashboard-placeholder">
        <div className="placeholder-content">
          <h3>Welcome to your new Dashboard</h3>
          <p>Start by adding students or creating your first course from the sidebar menu.</p>
        </div>
      </div>
    </div>
  );
};

export default DashboardOverview;
