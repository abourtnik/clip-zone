export function formatSizeUnits (bytes : number) : string {
    const units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    const power = bytes > 0 ? Math.floor(Math.log(bytes) / Math.log(1024)) : 0;
    return (bytes / Math.pow(1024, power)).toFixed(2) + ' ' + units[power];
}
