import React from 'react';
import { useQuery } from '@tanstack/react-query';
import { Users, BookOpen, GraduationCap, CheckSquare, Loader2 } from 'lucide-react';
import api from '../../services/api';
import './DashboardOverview.css';

const StatCard: React.FC<{ title: string; value: string | number; icon: React.ElementType; color: string }> = ({ title, value, icon: Icon, color }) => (
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
  const { data: stats, isLoading } = useQuery({ 
    queryKey: ['dashboard-summary'], 
    queryFn: () => api.get('/dashboard/stats').then(res => res.data) 
  });

  if (isLoading) return <div className="loading-state"><Loader2 className="spinner" /> Loading dashboard...</div>;

  return (
    <div className="overview-container">
      <div className="welcome-section">
        <h1>Overview</h1>
        <p>A quick summary of your school's current status.</p>
      </div>

      <div className="stats-grid">
        <StatCard title="Total Students" value={stats?.students || 0} icon={Users} color="blue" />
        <StatCard title="Total Courses" value={stats?.courses || 0} icon={BookOpen} color="indigo" />
        <StatCard title="Total Subjects" value={stats?.subjects || 0} icon={CheckSquare} color="green" />
        <StatCard title="Attendance Rate" value={stats?.attendance_rate || '0%'} icon={GraduationCap} color="purple" />
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
