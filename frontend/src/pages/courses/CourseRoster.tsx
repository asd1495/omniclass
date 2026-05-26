import React from 'react';
import { useParams, Link } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { ChevronLeft, Mail, User as UserIcon, Loader2 } from 'lucide-react';
import api from '../../services/api';
import '../students/StudentList.css';

interface Student {
  id: number;
  name: string;
  email: string;
}

interface Course {
  id: number;
  name: string;
}

const CourseRoster: React.FC = () => {
  const { id } = useParams<{ id: string }>();

  const { data: course, isLoading: courseLoading } = useQuery({
    queryKey: ['course', id],
    queryFn: async () => {
      const response = await api.get(`/courses/${id}`);
      return response.data.data as Course;
    },
  });

  const { data: students, isLoading: studentsLoading } = useQuery({
    queryKey: ['students', 'course', id],
    queryFn: async () => {
      const response = await api.get(`/students?course_id=${id}`);
      return response.data.data as Student[];
    },
  });

  if (courseLoading || studentsLoading) {
    return <div className="loading-state"><Loader2 className="spinner" /> Loading roster...</div>;
  }

  return (
    <div className="student-container">
      <div className="page-header">
        <div style={{ display: 'flex', alignItems: 'center', gap: '1rem' }}>
          <Link to="/courses" className="btn-icon">
            <ChevronLeft size={24} />
          </Link>
          <div>
            <h1>{course?.name}</h1>
            <p>Class Roster & Enrollment</p>
          </div>
        </div>
      </div>

      <div className="student-table-wrapper">
        <table className="student-table">
          <thead>
            <tr>
              <th>Student</th>
              <th>Email</th>
            </tr>
          </thead>
          <tbody>
            {students?.length === 0 ? (
              <tr>
                <td colSpan={2} className="empty-row">No students enrolled in this course.</td>
              </tr>
            ) : (
              students?.map(student => (
                <tr key={student.id}>
                  <td>
                    <div className="student-info-cell">
                      <div className="avatar-small"><UserIcon size={14} /></div>
                      <span>{student.name}</span>
                    </div>
                  </td>
                  <td>
                    <div className="email-cell">
                      <Mail size={14} />
                      <span>{student.email}</span>
                    </div>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default CourseRoster;
