import {
    Chart,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Tooltip,
} from 'chart.js';
import { Line } from 'react-chartjs-2';
import moment from "moment";

Chart.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Tooltip,
);

export default function LineChart({data}) {

    const options = {
        responsive: true,
        scales: {
            y: {
                min: 0,
                ticks: {
                    precision: 0,
                }
            }
        },
        plugins: {
            legend: {
                display : false
            },
            tooltip : {
                displayColors: false,
                borderColor: 'black',
                bodyFont: {
                    weight: 'bold',
                    size : 17,
                },
                borderWidth: 2,
                callbacks: {
                    title: () => null,
                    label: (context) => context.parsed.y + ' view' + (context.parsed.y > 1 ? 's' : ''),
                }
            }
        },
    };

    return (
        <Line options={options} data={{
            labels: JSON.parse(data).map(p => moment(p.date, 'YYYY-M-D').format("D MMM YYYY")),
            datasets: [
                {
                    data: JSON.parse(data).map(p => p.count),
                    borderColor: 'rgb(53, 162, 235)',
                    backgroundColor: 'rgba(53, 162, 235, 0.5)',
                },
            ],
        }} />
    );
}
