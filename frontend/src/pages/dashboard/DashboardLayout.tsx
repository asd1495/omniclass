import React, { useState } from 'react';
import { Link, useLocation, Outlet } from 'react-router-dom';
import { 
  LayoutDashboard, 
  Users, 
  BookOpen, 
  CheckSquare, 
  LogOut, 
  Menu, 
  User as UserIcon,
  GraduationCap,
  History
} from 'lucide-react';
import { useAuth } from '../../hooks/useAuth';
import './DashboardLayout.css';

const DashboardLayout: React.FC = () => {
  const [isSidebarOpen, setIsSidebarOpen] = useState(false);
  const { user, logout } = useAuth();
  const location = useLocation();

  const navigation = [
    { name: 'Dashboard', href: '/dashboard', icon: LayoutDashboard },
    { name: 'Students', href: '/students', icon: Users },
    { name: 'Courses', href: '/courses', icon: BookOpen },
    { name: 'Subjects', href: '/subjects', icon: CheckSquare },
    { name: 'Attendance', href: '/attendance', icon: GraduationCap },
    { name: 'History', href: '/attendance/history', icon: History },
  ];

  const isActive = (path: string) => location.pathname === path;

  return (
    <div className="dashboard-container">
      {/* Mobile Sidebar Overlay */}
      {isSidebarOpen && (
        <div className="sidebar-overlay" onClick={() => setIsSidebarOpen(false)} />
      )}

      {/* Sidebar */}
      <aside className={`sidebar ${isSidebarOpen ? 'open' : ''}`}>
        <div className="sidebar-header">
          <div className="logo-placeholder">OC</div>
          <span className="brand-name">OmniClass</span>
        </div>

        <nav className="sidebar-nav">
          {navigation.map((item) => (
            <Link
              key={item.name}
              to={item.href}
              className={`nav-item ${isActive(item.href) ? 'active' : ''}`}
              onClick={() => setIsSidebarOpen(false)}
            >
              <item.icon size={20} />
              <span>{item.name}</span>
            </Link>
          ))}
        </nav>

        <div className="sidebar-footer">
          <button onClick={logout} className="nav-item logout-btn">
            <LogOut size={20} />
            <span>Logout</span>
          </button>
        </div>
      </aside>

      {/* Main Content */}
      <main className="main-content">
        <header className="top-nav">
          <button className="mobile-menu-btn" onClick={() => setIsSidebarOpen(true)}>
            <Menu size={24} />
          </button>
          
          <div className="top-nav-right">
            <div className="user-profile">
              <div className="user-info">
                <span className="user-name">{user?.name}</span>
                <span className="user-role">Teacher</span>
              </div>
              <div className="user-avatar">
                <UserIcon size={20} />
              </div>
            </div>
          </div>
        </header>

        <section className="content-area">
          <Outlet />
        </section>
      </main>
    </div>
  );
};

export default DashboardLayout;
