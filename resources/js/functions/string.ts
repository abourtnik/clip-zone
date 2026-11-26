export function enumToArray(enumObject : any): { value: number, label: string }[]
{
    return Object.values(enumObject)
        .filter((v) => typeof v === 'number')
        .map(value => ({
            value,
            label: humanize(enumObject[value]),
        }))
}

export function humanize(str: string): string
{
    return str.toLowerCase()
        .replace(/_/g, ' ')
        .replace(/^\w/, c => c.toUpperCase());
}

export function truncate(str: string, limit: number, end: string = '...')
{
    return str.length > limit ? str.slice(0, limit) + end : str;
}

export function mimeToLabel (mime: string)
{
    return (mime.split("/")[1] ?? mime).split("+")[0].toUpperCase();
}
