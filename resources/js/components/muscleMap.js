export default (config = {}) => ({
    activeMuscles: Array.isArray(config.activeMuscles) ? config.activeMuscles : [],
    selectedMuscle: config.selectedMuscle || null,
    hoveredMuscle: null,
    hoveredSubMuscle: null,
    tooltipText: '',
    tooltipPos: { x: 0, y: 0 },
    tooltipVisible: false,
    viewMode: config.viewMode || 'both', // 'both', 'front', 'back'
    interactive: Boolean(config.interactive ?? false),

    init() {
        this.$nextTick(() => {
            this.updateSvgClasses();
        });

        this.$watch('activeMuscles', () => {
            this.updateSvgClasses();
        });

        this.$watch('selectedMuscle', () => {
            this.updateSvgClasses();
        });

        this.$watch('hoveredMuscle', () => {
            this.updateSvgClasses();
        });
    },

    isMuscleHighlighted(muscle) {
        if (!muscle) return false;
        const m = muscle.trim().toLowerCase();

        if (this.selectedMuscle && this.selectedMuscle.trim().toLowerCase() === m) {
            return true;
        }

        if (this.hoveredMuscle && this.hoveredMuscle.trim().toLowerCase() === m) {
            return true;
        }

        return this.activeMuscles.some(a => a && a.trim().toLowerCase() === m);
    },

    updateSvgClasses() {
        const paths = this.$root.querySelectorAll('.muscle-path');
        const hasFocus = Boolean(this.selectedMuscle || this.hoveredMuscle || this.activeMuscles.length > 0);
        this.$root.classList.toggle('has-focus', hasFocus);

        paths.forEach(path => {
            const muscle = path.getAttribute('data-muscle');
            const isHighlight = this.isMuscleHighlighted(muscle);
            const isHover = Boolean(this.hoveredMuscle && muscle && this.hoveredMuscle.trim().toLowerCase() === muscle.trim().toLowerCase());

            path.classList.toggle('is-highlighted', isHighlight);
            path.classList.toggle('is-hovered', isHover);
        });
    },

    onMuscleEnter(e) {
        const target = e.target.closest('.muscle-path');
        if (!target) return;

        const muscle = target.getAttribute('data-muscle');
        const subMuscle = target.getAttribute('data-tooltip') || '';
        
        if (!muscle) return;

        this.hoveredMuscle = muscle;
        this.hoveredSubMuscle = subMuscle;
        this.tooltipText = subMuscle && subMuscle !== muscle ? `${muscle} (${subMuscle})` : muscle;
        this.tooltipVisible = true;
        this.updateTooltipPos(e);
    },

    onMouseMove(e) {
        if (!this.tooltipVisible) return;
        this.updateTooltipPos(e);
    },

    updateTooltipPos(e) {
        const rect = this.$root.getBoundingClientRect();
        this.tooltipPos = {
            x: Math.max(10, Math.min(rect.width - 100, e.clientX - rect.left)),
            y: Math.max(10, e.clientY - rect.top - 35)
        };
    },

    onMuscleLeave() {
        this.hoveredMuscle = null;
        this.hoveredSubMuscle = null;
        this.tooltipVisible = false;
    },

    onMuscleClick(e) {
        if (!this.interactive) return;
        const target = e.target.closest('.muscle-path');
        if (!target) return;

        const muscle = target.getAttribute('data-muscle');
        if (muscle) {
            this.toggleMuscle(muscle);
        }
    },

    toggleMuscle(muscle) {
        if (this.selectedMuscle && this.selectedMuscle.toLowerCase() === muscle.toLowerCase()) {
            this.selectedMuscle = null;
        } else {
            this.selectedMuscle = muscle;
        }

        this.$dispatch('muscle-selected', {
            muscle: this.selectedMuscle
        });
    },

    clearSelection() {
        this.selectedMuscle = null;
        this.$dispatch('muscle-selected', {
            muscle: null
        });
    },

    setView(mode) {
        this.viewMode = mode;
    }
});
