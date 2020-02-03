<script>
import moment from 'moment';
export default {
    methods: {
        fulldate(date) {
            return moment(date).format('Do MMMM YYYY');
        }
    }
    
}
</script>