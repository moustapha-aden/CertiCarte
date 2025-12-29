@extends('layouts.app')

@section('title', 'Génération de Rapports')
@section('page-subtitle', 'Génération de Rapports')

@section('content')
    {{-- Page Header --}}
    <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl p-8 mb-8 text-white shadow-2xl">
        <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-6 sm:space-y-0 sm:space-x-8">
            {{-- Header Icon --}}
            <div class="shrink-0">
                <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
            </div>

            {{-- Header Text --}}
            <div class="flex-grow text-center sm:text-left">
                <h1 class="text-4xl font-extrabold mb-2">Génération de Rapports</h1>
                <p class="text-xl text-indigo-100 mb-4">Lycée Ahmed Farah Ali</p>
                <p class="text-lg text-indigo-200">
                    Générez des certificats, cartes d'étudiant et listes d'appel
                </p>
            </div>
        </div>
    </div>

    {{-- Reports Form --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
        {{-- Form Header --}}
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Sélectionner le Type de Rapport</h2>
            <p class="text-gray-600">Choisissez le type de document à générer et remplissez les informations requises
            </p>
        </div>

        {{-- Form Content --}}
        <div class="p-8">
            <form id="reportsForm" x-data="reportsForm()" @submit.prevent="generateReport">
                {{-- Step 1: Report Type Selection --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span
                            class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                        Type de Rapport
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Certificate Option --}}
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="reportType" value="certificate" x-model="reportType"
                                class="sr-only peer" @change="resetForm">
                            <div
                                class="p-6 border-2 border-gray-200 rounded-xl transition-all duration-200
                                        peer-checked:border-indigo-500 peer-checked:bg-indigo-50
                                        group-hover:border-gray-300 group-hover:shadow-md">
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Certificat</h4>
                                    <p class="text-sm text-gray-600">Certificat de scolarité</p>
                                </div>
                            </div>
                        </label>

                        {{-- ID Card Option --}}
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="reportType" value="id_card" x-model="reportType"
                                class="sr-only peer" @change="resetForm">
                            <div
                                class="p-6 border-2 border-gray-200 rounded-xl transition-all duration-200
                                        peer-checked:border-indigo-500 peer-checked:bg-indigo-50
                                        group-hover:border-gray-300 group-hover:shadow-md">
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                                            </path>
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Carte d'Étudiant</h4>
                                    <p class="text-sm text-gray-600">Carte d'identité étudiante</p>
                                </div>
                            </div>
                        </label>

                        {{-- Attendance List Option --}}
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="reportType" value="attendance_list" x-model="reportType"
                                class="sr-only peer" @change="resetForm">
                            <div
                                class="p-6 border-2 border-gray-200 rounded-xl transition-all duration-200
                                        peer-checked:border-indigo-500 peer-checked:bg-indigo-50
                                        group-hover:border-gray-300 group-hover:shadow-md">
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                            </path>
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Liste d'Appel</h4>
                                    <p class="text-sm text-gray-600">Liste de présence</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Step 2: Conditional Input Fields --}}
                <div x-show="reportType" class="mb-8" x-transition>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span
                            class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                        Paramètres du Rapport
                    </h3>

                    <div class="flex flex-col lg:flex-row gap-4">
                        {{-- School Year Selection --}}
                        <div class="flex-1">
                            <label for="school_year_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Année Scolaire <span class="text-red-500">*</span>
                            </label>
                            <select id="school_year_id" name="school_year_id" x-model="schoolYearId" @change="loadClasses"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="">Sélectionner une année scolaire</option>
                                @foreach ($schoolYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->year }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Class Selection --}}
                        <div class="flex-1">
                            <label for="classe_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Classe <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="classe_id" name="classe_id" x-model="classeId" @change="loadStudents"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    :disabled="!schoolYearId">
                                    <option value="">Sélectionner une classe</option>
                                    <template x-for="classe in classes" :key="classe.id">
                                        <option :value="classe.id" x-text="classe.label"></option>
                                    </template>
                                </select>
                                <div x-show="schoolYearId && isLoadingClasses"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="animate-spin h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Student Selection (only for certificate and ID card) --}}
                        <div x-show="reportType === 'certificate' || reportType === 'id_card'" x-transition
                            class="flex-1">
                            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Étudiant <span x-show="reportType === 'certificate' || reportType === 'id_card'"
                                    class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="student_id" name="student_id" x-model="studentId"
                                    :required="reportType === 'certificate' || reportType === 'id_card'"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    :disabled="!classeId">
                                    <option value="">Sélectionner un étudiant</option>
                                    <template x-for="student in students" :key="student.id">
                                        <option :value="student.id"
                                            x-text="student.name + ' (' + student.matricule + ')'"></option>
                                    </template>
                                </select>
                                <div x-show="classeId && isLoadingStudents"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="animate-spin h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Generate Button --}}
                <div x-show="reportType" class="flex justify-center" x-transition>
                    <button type="submit" :disabled="!canGenerate"
                        class="px-8 py-4 bg-indigo-600 text-white font-semibold rounded-xl
                                   hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500
                                   focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl
                                   disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-lg">
                        <span x-show="!isGenerating" class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Générer le Rapport
                        </span>
                        <span x-show="isGenerating" class="flex items-center">
                            <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Génération en cours...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- JavaScript for Form Handling --}}
    <script>
        function reportsForm() {
            return {
                reportType: '',
                schoolYearId: '',
                classeId: '',
                studentId: '',
                classes: [],
                students: [],
                isGenerating: false,
                isLoadingClasses: false,
                isLoadingStudents: false,

                get canGenerate() {
                    if (!this.reportType) return false;
                    if (!this.schoolYearId || !this.classeId) return false;

                    if (this.reportType === 'certificate' || this.reportType === 'id_card') {
                        return this.studentId !== '';
                    }

                    return true;
                },

                resetForm() {
                    this.schoolYearId = '';
                    this.classeId = '';
                    this.studentId = '';
                    this.classes = [];
                    this.students = [];
                },

                async loadClasses() {
                    if (!this.schoolYearId) {
                        this.classes = [];
                        this.classeId = '';
                        this.students = [];
                        this.studentId = '';
                        this.isLoadingClasses = false;
                        return;
                    }

                    this.isLoadingClasses = true;
                    this.classes = [];

                    try {
                        console.log('Loading classes for school year:', this.schoolYearId);
                        const response = await fetch(`/api/classes/by-year/${this.schoolYearId}`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        });

                        console.log('Response status:', response.status);

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();
                        console.log('Classes data received:', data);

                        if (data.success) {
                            // Convert the object format to array format for Alpine.js
                            this.classes = Object.entries(data.classes).map(([id, label]) => ({
                                id: parseInt(id),
                                label: label
                            }));
                            console.log('Classes loaded successfully:', this.classes);
                        } else {
                            console.error('Error loading classes:', data.message);
                            this.classes = [];
                            alert('Erreur lors du chargement des classes: ' + (data.message || 'Erreur inconnue'));
                        }
                    } catch (error) {
                        console.error('Error loading classes:', error);
                        this.classes = [];
                        alert('Erreur lors du chargement des classes: ' + error.message);
                    } finally {
                        this.isLoadingClasses = false;
                    }

                    this.classeId = '';
                    this.students = [];
                    this.studentId = '';
                },

                async loadStudents() {
                    if (!this.classeId || (this.reportType !== 'certificate' && this.reportType !== 'id_card')) {
                        this.students = [];
                        this.studentId = '';
                        this.isLoadingStudents = false;
                        return;
                    }

                    this.isLoadingStudents = true;
                    this.students = [];

                    try {
                        console.log('Loading students for class:', this.classeId);
                        const response = await fetch(`/api/students/by-class/${this.classeId}`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        });

                        console.log('Response status:', response.status);

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();
                        console.log('Students data received:', data);

                        if (data.success) {
                            this.students = data.students;
                            console.log('Students loaded successfully:', this.students);
                        } else {
                            console.error('Error loading students:', data.message);
                            this.students = [];
                            alert('Erreur lors du chargement des étudiants: ' + (data.message || 'Erreur inconnue'));
                        }
                    } catch (error) {
                        console.error('Error loading students:', error);
                        this.students = [];
                        alert('Erreur lors du chargement des étudiants: ' + error.message);
                    } finally {
                        this.isLoadingStudents = false;
                    }

                    this.studentId = '';
                },

                async generateReport() {
                    if (!this.canGenerate) return;

                    this.isGenerating = true;

                    try {
                        // Determine the correct route based on report type
                        let route = '';
                        let params = '';

                        switch (this.reportType) {
                            case 'certificate':
                                route = `/reports/certificate/${this.studentId}`;
                                break;
                            case 'id_card':
                                route = `/reports/id-card/${this.studentId}`;
                                break;
                            case 'attendance_list':
                                route = `{{ url('reports') }}/attendance-list/${this.classeId}`;
                                break;
                        }

                        // Open report in new tab
                        window.open(route, '_blank');

                    } catch (error) {
                        console.error('Error generating report:', error);
                        alert('Erreur lors de la génération du rapport. Veuillez réessayer.');
                    } finally {
                        this.isGenerating = false;
                    }
                }
            }
        }
    </script>
@endsection
