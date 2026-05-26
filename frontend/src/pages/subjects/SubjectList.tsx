import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { Plus, Trash2, CheckSquare, Loader2, AlertCircle } from 'lucide-react';
import axios from 'axios';
import api from '../../services/api';
import '../students/StudentList.css';

interface Subject {
  id: number;
  name: string;
}

const SubjectList: React.FC = () => {
  const queryClient = useQueryClient();
  const [isAdding, setIsAdding] = useState(false);
  const [name, setName] = useState('');
  const [formError, setFormError] = useState<string | null>(null);

  const { data, isLoading, isError } = useQuery({
    queryKey: ['subjects'],
    queryFn: async () => {
      const response = await api.get('/subjects');
      return response.data.data as Subject[];
    },
  });

  const createMutation = useMutation({
    mutationFn: (newName: string) => api.post('/subjects', { name: newName }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['subjects'] });
      setIsAdding(false);
      setName('');
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
    createMutation.mutate(name);
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
            <div className="form-group">
              <label>Subject Name</label>
              <input 
                type="text" 
                value={name} 
                onChange={e => setName(e.target.value)}
                placeholder="e.g. Mathematics, Science"
                required
              />
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
                <th className="text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              {data?.length === 0 ? (
                <tr>
                  <td colSpan={2} className="empty-row">No subjects found.</td>
                </tr>
              ) : (
                data?.map(subject => (
                  <tr key={subject.id}>
                    <td>
                      <div className="student-info-cell">
                        <div className="avatar-small"><CheckSquare size={14} /></div>
                        <span>{subject.name}</span>
                      </div>
                    </td>
                    <td className="text-right">
                      <button 
                        className="btn-icon delete" 
                        onClick={() => { if(window.confirm('Delete subject?')) deleteMutation.mutate(subject.id) }}
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

export default SubjectList;
