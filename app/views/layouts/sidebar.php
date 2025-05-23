<aside class="sidebar">
    <div class="sidebar-logo">
        <i class="fas fa-book-open"></i>
        <span>Biblioteca</span>
    </div>

    <!-- Información del usuario -->
    <div class="user-info">
        <div class="user-avatar">J</div>
        <div class="user-details">
            <div class="user-name">Juan Pérez</div>
            <div class="user-role">Administrador</div>
        </div>
    </div>

    <nav class="sidebar-menu">
        <ul>
            <!-- Dashboard -->
            <li class="<?php echo ($current_page == 'dashboard') ? 'active' : '' ?>">
                <a href="/dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <!-- Préstamos -->
            <li class="<?php echo ($current_page == 'prestamos') ? 'active' : '' ?>">
                <a href="<?php echo URL_ROOT; ?>/prestamos">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Préstamos</span>
                </a>
            </li>
            
            <!-- Estudiantes -->
            <li class="<?php echo ($current_page == 'students') ? 'active' : '' ?>">
                <a href="/students">
                    <i class="fas fa-user-graduate"></i>
                    <span>Estudiantes</span>
                </a>
            </li>
            
            <!-- Materias -->
            <li class="<?php echo ($current_page == 'materias') ? 'active' : '' ?>">
                <a href="<?php echo URL_ROOT; ?>/materias">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Materias</span>
                </a>
            </li>
            
            <!-- Libros -->
            <li class="<?php echo ($current_page == 'libros') ? 'active' : '' ?>">
                <a href="<?php echo URL_ROOT; ?>/libros">
                    <i class="fas fa-book"></i>
                    <span>Libros</span>
                </a>
            </li>
            
            <!-- Autores -->
            <li class="<?php echo ($current_page == 'autores') ? 'active' : '' ?>">
                <a href="<?php echo URL_ROOT; ?>/autores">
                    <i class="fas fa-feather-alt"></i>
                    <span>Autores</span>
                </a>
            </li>
            
            <!-- Editoriales -->
            <li class="<?php echo ($current_page == 'editoriales') ? 'active' : '' ?>">
                <a href="<?php echo URL_ROOT; ?>/editoriales">
                    <i class="fas fa-building"></i>
                    <span>Editoriales</span>
                </a>
            </li>
            
            <!-- Administración -->
            <li class="<?php echo ($current_page == 'administracion') ? 'active' : '' ?>">
                <a href="<?php echo URL_ROOT; ?>/administracion">
                    <i class="fas fa-cog"></i>
                    <span>Administración</span>
                </a>
            </li>
            
            <!-- Reportes -->
            <li class="<?php echo ($current_page == 'reportes') ? 'active' : '' ?>">
                <a href="<?php echo URL_ROOT; ?>/reportes">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reportes</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>