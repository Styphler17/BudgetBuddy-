import { useEffect, useRef } from 'react';

interface RichEditorProps {
    value: string;
    onChange: (content: string) => void;
    placeholder?: string;
    height?: number;
}

declare global {
    interface Window {
        tinymce: any;
    }
}

export const RichEditor = ({ value, onChange, placeholder, height = 400 }: RichEditorProps) => {
    const editorRef = useRef<any>(null);
    const containerRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (window.tinymce) {
            window.tinymce.init({
                target: containerRef.current,
                height: height,
                menubar: false,
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                setup: (editor: any) => {
                    editorRef.current = editor;
                    editor.on('change', () => {
                        onChange(editor.getContent());
                    });
                    editor.on('SkinLoaded', () => {
                        // Optional: force skin updates if needed
                    });
                },
                init_instance_callback: (editor: any) => {
                    if (value) editor.setContent(value);
                },
                content_style: 'body { font-family:Inter,Arial,sans-serif; font-size:16px; background-color: transparent !important; color: inherit !important; }',
                placeholder: placeholder,
                skin: 'oxide-dark',
                content_css: 'dark',
                branding: false,
                promotion: false
            });
        }

        return () => {
            if (editorRef.current) {
                editorRef.current.destroy();
            }
        };
    }, []);

    // Update content if value changes externally (and it's different from editor content)
    useEffect(() => {
        if (editorRef.current && value !== editorRef.current.getContent()) {
            editorRef.current.setContent(value || '');
        }
    }, [value]);

    return (
        <div className="rounded-md border border-input bg-background overflow-hidden min-h-[400px]">
            <div ref={containerRef} />
        </div>
    );
};
