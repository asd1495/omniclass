import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { Calendar, User as UserIcon, Loader2, BookOpen } from 'lucide-react';
import api from '../../services/api';
import './AttendanceTracker.css';

interface Student {
  id: number;
  name: string;
}

interface Course {
  id: number;
  name: string;
}

interface AttendanceRecord {
  student_id: number;
  status: string;
}

const AttendanceTracker: React.FC = () => {
  const queryClient = useQueryClient();
  const [selectedDate, setSelectedDate] = useState(new Date().toISOString().split('T')[0]);
  const [selectedCourse, setSelectedCourse] = useState<string>('');

  // Fetch Courses for filter
  const { data: courses } = useQuery({
    queryKey: ['courses'],
    queryFn: async () => {
      const response = await api.get('/courses');
      return response.data.data as Course[];
    },
  });

  // Fetch Students (filtered by course if selected)
  const { data: students, isLoading: studentsLoading } = useQuery({
    queryKey: ['students', selectedCourse],
    queryFn: async () => {
      const url = selectedCourse ? `/students?course_id=${selectedCourse}` : '/students';
      const response = await api.get(url);
      return response.data.data as Student[];
    },
  });

  // Fetch Existing Attendance for Date
  const { data: attendanceData, isLoading: attendanceLoading } = useQuery({
    queryKey: ['attendance', selectedDate],
    queryFn: async () => {
      const response = await api.get(`/attendance?date=${selectedDate}`);
      return response.data.data as AttendanceRecord[];
    },
  });

  // Update Attendance Mutation
  const mutation = useMutation({
    mutationFn: (data: { student_id: number, date: string, status: string }) => 
      api.post('/attendance', data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['attendance', selectedDate] });
    },
  });

  const getStatus = (studentId: number) => {
    return attendanceData?.find(a => a.student_id === studentId)?.status || 'pending';
  };

  const handleStatusChange = (studentId: number, status: string) => {
    mutation.mutate({ student_id: studentId, date: selectedDate, status });
  };

  const isLoading = studentsLoading || attendanceLoading;

  return (
    <div className="attendance-container">
      <div className="page-header">
        <div>
          <h1>Attendance Tracker</h1>
          <p>Record and monitor daily student attendance.</p>
        </div>
        <div className="tracker-filters">
          <div className="filter-group">
            <BookOpen size={16} />
            <select 
              value={selectedCourse} 
              onChange={(e) => setSelectedCourse(e.target.value)}
            >
              <option value="">All Courses</option>
              {courses?.map(course => (
                <option key={course.id} value={course.id}>{course.name}</option>
              ))}
            </select>
          </div>
          <div className="date-selector">
            <Calendar size={18} />
            <input 
              type="date" 
              value={selectedDate} 
              onChange={(e) => setSelectedDate(e.target.value)} 
            />
          </div>
        </div>
      </div>

      {isLoading ? (
        <div className="loading-state"><Loader2 className="spinner" /> Synchronizing records...</div>
      ) : (
        <div className="attendance-grid">
          <div className="attendance-header-row">
            <span>Student Name</span>
            <span className="status-header">Status</span>
          </div>
          
          <div className="attendance-body">
            {students?.map(student => (
              <div key={student.id} className="attendance-row">
                <div className="student-profile">
                  <div className="avatar-small"><UserIcon size={14} /></div>
                  <span>{student.name}</span>
                </div>
                
                <div className="status-options">
                  {['present', 'absent', 'late', 'excused'].map(status => (
                    <button
                      key={status}
                      className={`status-btn ${status} ${getStatus(student.id) === status ? 'active' : ''}`}
                      onClick={() => handleStatusChange(student.id, status)}
                      disabled={mutation.isPending && mutation.variables?.student_id === student.id}
                    >
                      {status.charAt(0).toUpperCase()}
                    </button>
                  ))}
                </div>
              </div>
            ))}
            {students?.length === 0 && (
              <div className="empty-row">No students found. Add students first to track attendance.</div>
            )}
          </div>
        </div>
      )}
    </div>
  );
};

export default AttendanceTracker;
