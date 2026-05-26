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

interface Subject {
  id: number;
  name: string;
  course_id?: number;
  course?: { id: number, name: string };
}

const SubjectRoster: React.FC = () => {
  const { id } = useParams<{ id: string }>();

  // Fetch Subject details
  const { data: subject, isLoading: subjectLoading } = useQuery({
    queryKey: ['subject', id],
    queryFn: async () => {
      const response = await api.get(`/subjects/${id}`);
      return response.data.data as Subject;
    },
  });

  // Fetch Students for the course linked to this subject
  const { data: students, isLoading: studentsLoading } = useQuery({
    queryKey: ['students', 'subject', id],
    queryFn: async () => {
      if (!subject?.course_id) return [] as Student[];
      const response = await api.get(`/students?course_id=${subject.course_id}`);
      return response.data.data as Student[];
    },
    enabled: !!subject,
  });

  if (subjectLoading || (subject?.course_id && studentsLoading)) {
    return <div className="loading-state"><Loader2 className="spinner" /> Loading subject roster...</div>;
  }

  return (
    <div className="student-container">
      <div className="page-header">
        <div style={{ display: 'flex', alignItems: 'center', gap: '1rem' }}>
          <Link to="/subjects" className="btn-icon">
            <ChevronLeft size={24} />
          </Link>
          <div>
            <h1>{subject?.name}</h1>
            <p>Roster via {subject?.course?.name || 'Unassigned Course'}</p>
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
            {!subject?.course_id ? (
              <tr>
                <td colSpan={2} className="empty-row">This subject is not assigned to any course.</td>
              </tr>
            ) : students?.length === 0 ? (
              <tr>
                <td colSpan={2} className="empty-row">No students enrolled in the associated course.</td>
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

export default SubjectRoster;
