export class DocumentModel {
    id;
    name;
    documentPath;
    description;
    format;
    project_id;
    folder_id;
    visualization_role_id;

    constructor({
        id,
        name,
        documentPath,
        description,
        format,
        projectId,
        folderId,
        visualizationRoleId,
    }) {
        this.id = id ?? ''
        this.name = name ?? '';
        this.documentPath = documentPath ?? '';
        this.description = description ?? '';
        this.format = format ?? '';
        this.project_id = projectId;
        this.folder_id= folderId;
        this.visualization_role_id= visualizationRoleId;
    }
}