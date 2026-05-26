import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { Plus, Trash2, CheckSquare, Loader2, AlertCircle, Users } from 'lucide-react';
import { Link } from 'react-router-dom';
import axios from 'axios';
import api from '../../services/api';
import '../students/StudentList.css';

interface Subject {
  id: number;
  name: string;
  course?: { id: number, name: string };
}

interface Course {
  id: number;
  name: string;
}

const SubjectList: React.FC = () => {
  const queryClient = useQueryClient();
  const [isAdding, setIsAdding] = useState(false);
  const [formData, setFormData] = useState({ name: '', course_id: '' });
  const [formError, setFormError] = useState<string | null>(null);

  const { data: courses } = useQuery({
    queryKey: ['courses'],
    queryFn: async () => {
      const response = await api.get('/courses');
      return response.data.data as Course[];
    },
  });

  const { data, isLoading, isError } = useQuery({
    queryKey: ['subjects'],
    queryFn: async () => {
      const response = await api.get('/subjects');
      return response.data.data as Subject[];
    },
  });

  const createMutation = useMutation({
    mutationFn: (newSubject: typeof formData) => api.post('/subjects', newSubject),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['subjects'] });
      setIsAdding(false);
      setFormData({ name: '', course_id: '' });
    },
    onError: (err: unknown) => {
      if (axios.isAxiosError(err)) {
        setFormError(err.response?.data?.message || 'Failed to create subject');
      } else {
        setFormError('An unexpected error occurred');
      }
    }
  });

  const deleteMutation = useMutation({
    mutationFn: (id: number) => api.delete(`/subjects/${id}`),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['subjects'] });
    },
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setFormError(null);
    createMutation.mutate(formData);
  };

  if (isLoading) return <div className="loading-state"><Loader2 className="spinner" /> Loading subjects...</div>;

  return (
    <div className="student-container">
      <div className="page-header">
        <div>
          <h1>Subjects</h1>
          <p>Manage the topics taught at your school.</p>
        </div>
        <button className="btn btn-primary" onClick={() => setIsAdding(!isAdding)}>
          {isAdding ? 'Cancel' : <><Plus size={18} /> Add Subject</>}
        </button>
      </div>

      {isAdding && (
        <div className="add-student-card">
          <form onSubmit={handleSubmit} className="student-form">
            <div className="form-grid">
              <div className="form-group">
                <label>Subject Name</label>
                <input 
                  type="text" 
                  value={formData.name} 
                  onChange={e => setFormData({...formData, name: e.target.value})}
                  placeholder="e.g. Mathematics, Science"
                  required
                />
              </div>
              <div className="form-group">
                <label>Assigned Course</label>
                <select 
                  value={formData.course_id} 
                  onChange={e => setFormData({...formData, course_id: e.target.value})}
                >
                  <option value="">Select a Course (Optional)</option>
                  {courses?.map(course => (
                    <option key={course.id} value={course.id}>{course.name}</option>
                  ))}
                </select>
              </div>
            </div>
            {formError && <div className="error-alert"><AlertCircle size={16}/> {formError}</div>}
            <div className="form-actions">
              <button type="submit" className="btn btn-primary" disabled={createMutation.isPending}>
                {createMutation.isPending ? 'Saving...' : 'Save Subject'}
              </button>
            </div>
          </form>
        </div>
      )}

      {isError ? (
        <div className="error-state">Failed to load subjects.</div>
      ) : (
        <div className="student-table-wrapper">
          <table className="student-table">
            <thead>
              <tr>
                <th>Subject Name</th>
                <th>Course</th>
                <th className="text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              {data?.length === 0 ? (
                <tr>
                  <td colSpan={3} className="empty-row">No subjects found.</td>
                </tr>
              ) : (
                data?.map(subject => (
                  <tr key={subject.id}>
                    <td>
                      <Link to={`/subjects/${subject.id}`} className="student-info-cell" style={{ textDecoration: 'none', color: 'inherit' }}>
                        <div className="avatar-small"><CheckSquare size={14} /></div>
                        <span style={{ fontWeight: 500 }}>{subject.name}</span>
                      </Link>
                    </td>
                    <td>
                       <span className="course-tag">{subject.course?.name || 'Unassigned'}</span>
                    </td>
                    <td className="text-right">
                       <div style={{ display: 'flex', gap: '0.5rem', justifyContent: 'flex-end' }}>
                        <Link to={`/subjects/${subject.id}`} className="btn-icon">
                           <Users size={18} />
                        </Link>
                        <button 
                          className="btn-icon delete" 
                          onClick={() => { if(window.confirm('Delete subject?')) deleteMutation.mutate(subject.id) }}
                        >
                          <Trash2 size={18} />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
};

export default SubjectList;
