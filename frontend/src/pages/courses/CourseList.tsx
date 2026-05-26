import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { Plus, Trash2, BookOpen, Loader2, AlertCircle } from 'lucide-react';
import axios from 'axios';
import api from '../../services/api';
import '../students/StudentList.css'; // Reusing base layout styles

interface Course {
  id: number;
  name: string;
}

const CourseList: React.FC = () => {
  const queryClient = useQueryClient();
  const [isAdding, setIsAdding] = useState(false);
  const [name, setName] = useState('');
  const [formError, setFormError] = useState<string | null>(null);

  const { data, isLoading, isError } = useQuery({
    queryKey: ['courses'],
    queryFn: async () => {
      const response = await api.get('/courses');
      return response.data.data as Course[];
    },
  });

  const createMutation = useMutation({
    mutationFn: (newName: string) => api.post('/courses', { name: newName }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['courses'] });
      setIsAdding(false);
      setName('');
    },
    onError: (err: unknown) => {
      if (axios.isAxiosError(err)) {
        setFormError(err.response?.data?.message || 'Failed to create course');
      } else {
        setFormError('An unexpected error occurred');
      }
    }
  });

  const deleteMutation = useMutation({
    mutationFn: (id: number) => api.delete(`/courses/${id}`),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['courses'] });
    },
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setFormError(null);
    createMutation.mutate(name);
  };

  if (isLoading) return <div className="loading-state"><Loader2 className="spinner" /> Loading courses...</div>;

  return (
    <div className="student-container">
      <div className="page-header">
        <div>
          <h1>Courses</h1>
          <p>Define grade levels and school groups.</p>
        </div>
        <button className="btn btn-primary" onClick={() => setIsAdding(!isAdding)}>
          {isAdding ? 'Cancel' : <><Plus size={18} /> Add Course</>}
        </button>
      </div>

      {isAdding && (
        <div className="add-student-card">
          <form onSubmit={handleSubmit} className="student-form">
            <div className="form-group">
              <label>Course Name</label>
              <input 
                type="text" 
                value={name} 
                onChange={e => setName(e.target.value)}
                placeholder="e.g. 1st Grade A"
                required
              />
            </div>
            {formError && <div className="error-alert"><AlertCircle size={16}/> {formError}</div>}
            <div className="form-actions">
              <button type="submit" className="btn btn-primary" disabled={createMutation.isPending}>
                {createMutation.isPending ? 'Saving...' : 'Save Course'}
              </button>
            </div>
          </form>
        </div>
      )}

      {isError ? (
        <div className="error-state">Failed to load courses.</div>
      ) : (
        <div className="student-table-wrapper">
          <table className="student-table">
            <thead>
              <tr>
                <th>Course Name</th>
                <th className="text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              {data?.length === 0 ? (
                <tr>
                  <td colSpan={2} className="empty-row">No courses found.</td>
                </tr>
              ) : (
                data?.map(course => (
                  <tr key={course.id}>
                    <td>
                      <div className="student-info-cell">
                        <div className="avatar-small"><BookOpen size={14} /></div>
                        <span>{course.name}</span>
                      </div>
                    </td>
                    <td className="text-right">
                      <button 
                        className="btn-icon delete" 
                        onClick={() => { if(window.confirm('Delete course?')) deleteMutation.mutate(course.id) }}
                      >
                        <Trash2 size={18} />
                      </button>
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

export default CourseList;
