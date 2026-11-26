<?php
/**
 * Illustrative "Operations Hub" dashboard from the Figma design.
 * Built in HTML/CSS (not an image) so it stays sharp on every screen.
 *
 * Set $mockVariant = 'hero' (with floating badges) or 'case' (tilted, blue background)
 * before including. It is decorative, so screen readers get one short description.
 */
$mockVariant = $mockVariant ?? 'hero';
$bars = [1, 2, 3, 4, 5, 6, 7]; // bar heights are set in style.css (.mock-bar:nth-child)
$days = ['M', 'T', 'W', 'T', 'F', 'S', 'S'];
?>
<div class="mock mock-<?= e($mockVariant) ?>" role="img" aria-label="Example operations dashboard showing active jobs, completed work, team utilisation, workflow status and priority tasks">
    <div class="mock-window" aria-hidden="true">
        <div class="mock-titlebar">
            <span class="mock-dots"><i></i><i></i><i></i></span>
            <span class="mock-title">Operations Hub</span>
            <span class="mock-avatar">WN</span>
        </div>
        <div class="mock-body">
            <aside class="mock-sidebar">
                <span class="mock-logo"><?= logo_mark(20, true) ?></span>
                <span class="mock-nav is-active"><?= icon('square', 9) ?> Overview</span>
                <span class="mock-nav"><?= icon('square', 9) ?> Jobs</span>
                <span class="mock-nav"><?= icon('square', 9) ?> Team</span>
                <span class="mock-nav"><?= icon('square', 9) ?> Clients</span>
                <span class="mock-nav"><?= icon('square', 9) ?> Reports</span>
                <?php if ($mockVariant === 'case'): ?>
                <span class="mock-nav mock-nav-bottom"><?= icon('square', 9) ?> Settings</span>
                <?php endif; ?>
            </aside>
            <div class="mock-main">
                <div class="mock-head">
                    <div>
                        <span class="mock-date">Monday, 24 June</span>
                        <span class="mock-h">Operations overview</span>
                    </div>
                    <span class="mock-btn">+ New job</span>
                </div>
                <div class="mock-stats">
                    <div class="mock-card"><span class="mock-label">Active jobs</span><b>24</b><span class="mock-trend">↑ 12%</span></div>
                    <div class="mock-card"><span class="mock-label">Completed this month</span><b>148</b><span class="mock-sub">31 this week</span></div>
                    <div class="mock-card"><span class="mock-label">Team utilisation</span><b>86%</b><span class="mock-progress"><i></i></span></div>
                </div>
                <div class="mock-row">
                    <div class="mock-card mock-chart">
                        <div class="mock-card-head"><span class="mock-label strong">Workflow status</span><span class="mock-sub">This week</span></div>
                        <div class="mock-bars">
                            <?php foreach ($bars as $i => $h): ?>
                            <span class="mock-bar<?= in_array($i, [4, 5], true) ? ' is-strong' : '' ?>"><em><?= e($days[$i]) ?></em></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mock-card mock-tasks">
                        <div class="mock-card-head"><span class="mock-label strong">Priority tasks</span><span class="mock-sub">View all</span></div>
                        <div class="mock-task"><span class="mock-check"></span><span><b>Approve site report</b><small>Due today</small></span><i class="dot dot-amber"></i></div>
                        <div class="mock-task"><span class="mock-check is-done"><?= icon('check', 9) ?></span><span><b>Review stock variance</b><small>Completed</small></span><i class="dot dot-green"></i></div>
                        <div class="mock-task"><span class="mock-check"></span><span><b>Assign service job</b><small>Tomorrow</small></span><i class="dot dot-blue"></i></div>
                    </div>
                </div>
                <div class="mock-card mock-activity">
                    <div class="mock-card-head"><span class="mock-label strong">Recent activity</span><span class="mock-sub">Last 24 hours</span></div>
                    <div class="mock-act"><span class="mock-initials">LH</span><span><b>Lebokane</b> completed Service Job #1048</span><small>09:42</small></div>
                    <div class="mock-act"><span class="mock-initials teal">TM</span><span><b>Thabo M.</b> submitted an inspection report</span><small>08:15</small></div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($mockVariant === 'hero'): ?>
    <div class="mock-float mock-float-top" aria-hidden="true">
        <span class="mock-float-icon"><?= icon('link', 14) ?></span>
        <span><b>Workflow automated</b><small>2 minutes ago</small></span>
    </div>
    <div class="mock-float mock-float-bottom" aria-hidden="true">
        <i class="dot dot-green pulse"></i><b>All systems operational</b>
    </div>
    <?php endif; ?>
</div>
